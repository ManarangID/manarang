<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\DataTables\RolesDataTable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-roles')) {
			$roles = Role::all();
			return view('admin.roles.datatable', compact('roles'));
		} else {
			return abort('404');
		}
    }
	
	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function anyData(Request $request)
	{
		$roles = Role::select('roles.*');
		return DataTables::of($roles)
			->addColumn('check', function ($role) {
				$check = '<div style="text-align:center;">
					<input type="checkbox" id="titleCheckdel" />
					<input type="hidden" class="deldata" name="id[]" value="'.Hashids::encode($role->id).'" disabled />
				</div>';
				return $check;
			})
            ->addColumn('action', function ($role) {
				$btn = '<div style="text-align:center;"><div class="btn-group">';
				$btn .= '<a href="'.url('dashboard/roles/'.Hashids::encode($role->id).'').'" class="btn btn-secondary btn-xs btn-icon" title="'.__('general.view').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-eye"></i></a> ';
				$btn .= '<a href="'.url('dashboard/roles/'.Hashids::encode($role->id).'/edit').'" class="btn btn-primary btn-xs btn-icon" title="'.__('general.edit').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i></a> ';
				$btn .= '<a href="'.url('dashboard/roles/'.Hashids::encode($role->id).'').'" class="btn btn-danger btn-xs btn-icon" data-delete="" title="'.__('general.delete').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash"></i></a>';
				$btn .= '</div></div>';
				return $btn;
            })
			->addColumn('control', function ($role) {
				$check = '<div style="text-align:center;"><a href="javascript:void(0);" class="btn btn-secondary btn-xs btn-icon"><i class="fa fa-plus"></i></a></div>';
				return $check;
			})
			->escapeColumns([])
			->make(true);
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if(Auth::user()->can('create-roles')) {
			return view('admin.roles.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if(Auth::user()->can('create-roles')) {
			$this->validate($request, ['name' => 'required|string|max:50']);
			$role = Role::firstOrCreate(['name' => $request->name]);

			return redirect()->route('roles.index')->with('success', __('role.store_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(Auth::user()->can('read-roles')) {
			$ids = Hashids::decode($id);
			$role = Role::findOrFail($ids[0]);

			return view('backend.roles.show', compact('role'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(Auth::user()->can('update-roles')) {
			$ids = Hashids::decode($id);
			$role = Role::findOrFail($ids[0]);
    
			$permissions = null;
			$hasPermission = null;
			
			$roles = Role::all()->pluck('name');
			
			$getRole = Role::findByName($role->name);
			
			$hasPermission = DB::table('role_has_permissions')
				->select('permissions.name')
				->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
				->where('role_id', $getRole->id)->get()->pluck('name')->all();
			
			$permissions = Permission::all()->pluck('name');

			return view('admin.roles.edit', compact('role', 'permissions', 'hasPermission'));
		} else {
			return abort('401');
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(Auth::user()->can('update-roles')) {
			$this->validate($request, ['name' => 'required']);

			$ids = Hashids::decode($id);
			$role = Role::findOrFail($ids[0]);
			$role->update(['name' => $request->name]);

			if ($request->has('permission')) {
				$role->syncPermissions($request->permission);
			}

			return redirect()->route('roles.index')->with('success', __('role.update_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
		if(Auth::user()->can('delete-roles')) {
			$ids = Hashids::decode($id);
			Role::destroy($ids[0]);

			return redirect()->route('roles.index')->with('success', __('roles.destroy_notif'));
		} else {
			return abort('404');
		}
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function deleteAll(Request $request): RedirectResponse
    {
		if(Auth::user()->can('delete-roles')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
        		Role::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('role.destroy_notif'));
			} else {
				return redirect()->route('roles.index')->with('error', __('role.destroy_error_notif'));
			}
		} else {
			return abort('404');
		}
         
    }
}
