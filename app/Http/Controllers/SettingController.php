<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\SettingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $settings = Setting::all();
        return view('components.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $setting = Setting::findOrFail($id);

        //render view with setting
        return view('components.settings.edit', ['setting' => $setting]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, string $id): RedirectResponse
    {
        $setting = Setting::findOrFail($id);
        if ($id == 15 || $id == 16){
            if ($request->hasFile('image')) {
                //delete old image
                Storage::delete('public/images/'.$setting->value);
                //upload image
                $image = $request->file('image');
                $image->storeAs('public/images', $request->value);

                $setting->update($request->validated());
            }else{
                $setting->update($request->validated());
            }
            return redirect(route('settings'))->with('success', 'Updated!');
        }else{ 
            $setting->update($request->validated());
            return redirect(route('settings'))->with('success', 'Updated!'); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
