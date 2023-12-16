<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Theme;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class ThemeController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request): View
    {
		if(Auth::user()->can('read-themes')) {
			$themes = Theme::leftJoin('users', 'users.id', '=', 'themes.created_by')
				->select('themes.*', 'users.id as uid', 'users.name as uname')
				->get();
			return view('admin.theme.datatable', compact('themes'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
		if(Auth::user()->can('create-themes')) {
			return view('admin.theme.create');
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-themes')) {
			$this->validate($request,[
				'title' => 'required',
				'author' => 'required',
				'folder' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Theme::create($requestData);
			
			return redirect()->route('themes.index')->with('success', __('theme.store_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request): View
    {
		if(Auth::user()->can('read-themes')) {
			return view('admin.theme.install');
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id): View
    {
		if(Auth::user()->can('update-themes')) {
			$ids = Hashids::decode($id);
			$theme = Theme::findOrFail($ids[0]);

			return view('admin.theme.edit', compact('theme'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id): RedirectResponse
    {
		if(Auth::user()->can('update-themes')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required',
				'active' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$theme = Theme::findOrFail($ids[0]);
			$theme->update($requestData);

			return redirect()->route('themes.index')->with('success', __('theme.update_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
		if(Auth::user()->can('delete-themes')) {
			$ids = Hashids::decode($id);
			Theme::destroy($ids[0]);
			$title = $request->title;
			File::deleteDirectory(public_path('frontend/'.$title));
			return redirect()->route('themes.index')->with('success', __('theme.destroy_notif'));
		} else {
			return redirect('forbidden');
		}
    }
	
    public function active($id): RedirectResponse
    {
		if(Auth::user()->can('update-themes')) {
			$ids = Hashids::decode($id);
			$theme = Theme::findOrFail($ids[0]);
			
			Theme::where('active', '=', 'Y')->update([
				'active' => 'N',
				'updated_by' => Auth::User()->id
			]);
			
			$theme->update([
				'active' => 'Y',
				'updated_by' => Auth::User()->id
			]);

			return redirect()->route('themes.index')->with('success', __('theme.active_notif'));
		} else {
			return redirect('forbidden');
		}
    }
	
	public function install(Request $request): View
    {
		if(Auth::user()->can('read-themes')) {
			return view('admin.theme.install');
		} else {
			return redirect('forbidden');
		}
    }
	
	public function processInstall(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-themes')) {
			$this->validate($request, [
				'files' => 'required|mimetypes:application/zip,application/octet-stream'
			]);
			
			if ($request->file('files')->isValid()) {
				$zip = new ZipArchive();
				$status = $zip->open($request->file("files")->getRealPath());
				$file = $request->file('files')->getClientOriginalName();
				$filename = pathinfo($file, PATHINFO_FILENAME);
				if ($status !== true) {
					throw new \Exception($status);
				}
				else{
					$storageDestinationPath = public_path("frontend/".$filename);
			
					if (!\File::exists( $storageDestinationPath)) {
						\File::makeDirectory($storageDestinationPath, 0755, true);
					}
					$zip->extractTo($storageDestinationPath);
					$zip->close();
					Theme::create([
						'title' => $filename,
						'author' => Auth::user()->name,
						'folder' => $filename,
						'active' => 'N',
						'created_by' => Auth::User()->id,
						'updated_by' => Auth::User()->id
					]);
					return redirect()->route('themes.index')->with('success', __('theme.store_notif'));
				}
			} else {
				return back()->with('error', __('theme.install_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
	
	protected function importAssets($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/themes/'.$filename.'/Asset')));
		$files = File::directories($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/frontend/'.$pathinfo['basename'])));
			if(!File::isDirectory($newpath)){
				File::moveDirectory($oldpath, $newpath);
			}
		}
	}
	
	protected function importViews($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/themes/'.$filename.'/View')));
		$files = File::directories($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = base_path('resources/views/frontend/'.$pathinfo['basename']);
			if(!File::isDirectory($newpath)){
				File::moveDirectory($oldpath, $newpath);
			}
		}
	}
}
