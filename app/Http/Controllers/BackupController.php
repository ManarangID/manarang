<?php

namespace App\Http\Controllers;

use auth;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Backup\Helpers\Format;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\View\Components\Alert;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => Format::humanReadableSize($disk->size($f)),
                    'last_modified' => Carbon::createFromTimestamp($disk->lastModified($f)),
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);
        return view("admin.settings.backups")->with(compact('backups'));
    }
    public function create(): RedirectResponse
    {
        try {
            // start the backup process
            Artisan::call('backup:run', ['--only-db' => 'true']);
            $output = Artisan::output();
            // log the results
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            return redirect()->route('backups.index')->with('success', __('backup.store_notif'));
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }
    /**
     * Downloads a backup zip file.
     *
     * TODO: make it work no matter the flysystem driver (S3 Bucket, etc).
     */
    public function download($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) {
            $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);
            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
    /**
     * Deletes a backup file.
     */
    public function destroy($file_name): RedirectResponse
    {
        Storage::delete('public/ManarangID/'.$file_name);
        return redirect()->route('backups.index')->with('success', __('backup.destroy_notif'));
    }
}
