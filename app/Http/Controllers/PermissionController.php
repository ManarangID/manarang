<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(): View
    {
		if(Auth::user()->can('read-permissions')) {
			$permissions = Permission::all();
			return view('admin.permissions.datatable', compact('permissions'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): View
    {
		if(Auth::user()->can('create-permissions')) {
			return view('admin.permissions.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-permissions')) {
			$this->validate($request, ['name' => 'required|string|unique:permissions']);
			$create = Permission::firstOrCreate(['name' => 'create-'.$request->name]);
			$read = Permission::firstOrCreate(['name' => 'read-'.$request->name]);
			$update = Permission::firstOrCreate(['name' => 'update-'.$request->name]);
			$delete = Permission::firstOrCreate(['name' => 'delete-'.$request->name]);

			return redirect()->route('permissions.index')->with('success', __('permission.store_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
		if(Auth::user()->can('read-permissions')) {
			$ids = Hashids::decode($id);
			$permission = Permission::findOrFail($ids[0]);

			return view('admin.permissions.show', compact('permission'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id): View
    {
		if(Auth::user()->can('update-permissions')) {
			$ids = Hashids::decode($id);
			$permission = Permission::findOrFail($ids[0]);

			return view('admin.permissions.edit', compact('permission'));
		} else {
			return abort('401');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return void
     */
    public function update(Request $request, $id): RedirectResponse
    {
		if(Auth::user()->can('update-permissions')) {
			$this->validate($request, ['name' => 'required']);

			$ids = Hashids::decode($id);
			$permission = Permission::findOrFail($ids[0]);
			$permission->update($request->all());

			return redirect()->route('permissions.index')->with('success', __('permission.update_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id): RedirectResponse
    {
		if(Auth::user()->can('delete-permissions')) {
			$ids = Hashids::decode($id);
			Permission::destroy($ids[0]);

			return redirect()->route('permissions.index')->with('success', __('permission.destroy_notif'));
		} else {
			return abort('401');
		}
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function deleteAll(Request $request)
    {
		if(Auth::user()->can('delete-permissions')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
        Permission::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('permission.destroy_notif'));
			} else {
				return redirect()->route('permissions.index')->with('error', __('permission.destroy_error_notif'));
			}
		} else {
			return abort('401');
		}
    }
}
