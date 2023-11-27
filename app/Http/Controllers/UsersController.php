<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\DataTables\UsersDataTable;
use Spatie\Permission\Models\Role;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(): View
    {
		if(Auth::user()->can('read-users')) {
			if (Auth::user()->hasRole('superadmin')) {
				$users = User::all();
			} elseif (Auth::user()->hasRole('member') || Auth::user()->hasRole('editor')) {
				$users = User::where('users.id', '!=', '1')
					->where('users.id', '=', Auth::user()->id)
					->get();
			} else {
				$users = User::where('users.id', '!=', '1')
					->get();
			}
			return view('admin.users.datatable', compact('users'));
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
		if(Auth::user()->can('create-users')) {
			$roles = Role::orderBy('name', 'ASC')->get();
			
			return view('admin.users.create', compact('roles'));
		} else {
			return abort('404');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-users')) {
			$this->validate($request,[
				'name' => 'required',
				'email' => 'required|string|max:255|email|unique:users',
				'password' => 'required',
				'roles' => 'required'
			]);

			$username = str_replace(' ', '', strtolower($request->name)) . mt_rand(15, 50);
				
				$request->request->add([
					'username' => $username,
					'picture' => '',
					'block' => 'N',
					'created_by' => Auth::User()->id,
					'updated_by' => Auth::User()->id
				]);
				$data = $request->except('password');
				$data['password'] = bcrypt($request->password);
				$user = User::create($data);
				
				$user->assignRole($request->roles);
				
				return redirect()->route('users.index')->with('success', __('user.store_notif'));
			
		} else {
			return abort('404');
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit(Request $request, $id): View
    {
		if(Auth::user()->can('update-users')) {
			$ids = Hashids::decode($id);
			
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				if (Auth::user()->hasRole('superadmin')) {
					$roles = Role::orderBy('name', 'ASC')->get();
				} else {
					$roles = Role::where('id', '!=', '1')->orderBy('name', 'ASC')->get();
				}

				$user = User::select('id', 'username', 'name', 'email', 'telp', 'bio', 'block', 'profile_photo_path')->findOrFail($ids[0]);
				
				return view('admin.users.edit', compact('user', 'roles'));
			} else {
				if (Auth::user()->id == $ids[0]) {
					$roles = Role::where('id', '!=', '1')->orderBy('name', 'ASC')->get();

					$user = User::select('id', 'username', 'name', 'email', 'telp', 'bio', 'block', 'profile_photo_path')->findOrFail($ids[0]);
					
					return view('admin.users.edit', compact('user', 'roles'));
				} else {
					return redirect()->route('users.index', Hashids::encode(Auth::user()->id));
				}
			}
		} else {
			return abort('404');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id): RedirectResponse
    {
		if(Auth::user()->can('update-users')) {
			$ids = Hashids::decode($id);
			$user = User::findOrFail($ids[0]);
			
			$this->validate($request,[
				'name' => 'required',
				'email' => 'required|string|max:255|email|unique:users,email,' . $ids[0],
			]);

			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			
			if ($request->hasFile('image')) {
				//delete old image
				Storage::delete('public/profile-photos/'.$user->profile_photo_path);
				
				$path = public_path('storage/profile-photos/');
				!is_dir($path) && mkdir($path, 0777, true);
	
				$name = $request->image->hashName();
				ResizeImage::make($request->file('image'))
					->resize(600, 600)
					->save($path . $name);
				

				$user->update([
					'profile_photo_path' => $name
				]);
			}
			
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				if ($request->input('password') == '' || $request->input('password') == null) {
					$data = $request->except('password');
					$user->update($data);
				} else {
					$data = $request->except('password');
					$data['password'] = bcrypt($request->password);
					$user->update($data);
				}

				$user->syncRoles($request->roles);
				
				if (Auth::user()->hasRole('member')) {
					return redirect()->route('users.edit', Hashids::encode(Auth::user()->id))->with('success', 'User updated!');
				} else {
					return redirect()->route('users.index')->with('success', __('user.update_notif'));
				}
			} else {
				if ($request->input('password') == '' || $request->input('password') == null) {
					$data = $request->except('password');
					$user->update($data);
				} else {
					$data = $request->except('password');
					$data['password'] = bcrypt($request->password);
					$user->update($data);
				}

				return redirect()->route('users.index')->with('success', __('user.update_notif'));
			}
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
    public function destroy($id): RedirectResponse
    {
		if(Auth::user()->can('delete-users')) {
			$ids = Hashids::decode($id);
			User::destroy($ids[0]);

			return redirect()->route('users.index')->with('success', __('user.destroy_notif'));
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
		if(Auth::user()->can('delete-users')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
        		User::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('user.destroy_notif'));
			} else {
				return redirect()->route('users.index')->with('error', __('user.destroy_error_notif'));
			}
		} else {
			return abort('404');
		}
    }
	
	public function getUser( $id)
	{
		//$user = User::find($id);
		//return response()->json(['data' => $user]);
        //return response()->json($user);
	}
	
	public function getUserNotMe(Request $request)
	{
		if(Auth::user()->can('read-users')) {
			$term = trim($request->q);

			if (empty($term)) {
				$users = User::select('id', 'name')->where([['id', '>', 1],['id', '!=', Auth::user()->id],['block', '=', 'N']])->limit(20)->get();
			} else {
				$users = User::select('id', 'name')->where([['name', 'LIKE', '%'.$term.'%'],['id', '>', 1],['id', '!=', Auth::user()->id],['block', '=', 'N']])->get();
			}

			$fusers = [];

			foreach ($users as $user) {
				$fusers[] = ['id' => $user->id, 'text' => $user->name];
			}

			return Response::json($fusers);
		} else {
			return abort('404');
		}
	}
}
