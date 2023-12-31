<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request): Response
    {
		if(Auth::user()->can('read-menumanager')) {
			$menu = new Menu;
			$menulist = $menu->tree();
			
			return response(view('admin.menumanager.index')->with('menu', $menulist));
		} else {
			return abort('401');
		}
    }
	
	/**
	 * Displays datatables front end view
	 *
	 * @return \Illuminate\View\View
	 */
    public function getIndex(): View
	{
		if(Auth::user()->can('read-menumanager')) {
			$menumanagers = Menu::leftJoin('users', 'users.id', '=', 'menus.created_by')
			->select('menus.*', 'users.id as uid', 'users.name as uname')->get();
			return view('admin.menumanager.datatable', compact('menumanagers'));
		} else {
			return redirect('forbidden');
		}
	}
	
	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function anyData()
	{
		$menumanagers = Menu::leftJoin('users', 'users.id', '=', 'menus.created_by')
			->select('menus.*', 'users.id as uid', 'users.name as uname');
		return Datatables::of($menumanagers)
			->addColumn('check', function ($menumanager) {
				$check = '<div style="text-align:center;">
					<input type="checkbox" id="titleCheckdel" />
					<input type="hidden" class="deldata" name="id[]" value="'.Hashids::encode($menumanager->id).'" disabled />
				</div>';
				return $check;
			})
			->addColumn('parent', function ($menumanager) {
				return $menumanager->parent == 0 ? __('menumanager.no_parent') : $menumanager->mainParent->title;
			})
            ->addColumn('action', function ($menumanager) {
				$btn = '<div style="text-align:center;"><div class="btn-group">';
				$btn .= '<a href="'.url('dashboard/menu-manager/'.Hashids::encode($menumanager->id).'').'" class="btn btn-secondary btn-xs btn-icon" title="'.__('general.view').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-eye"></i></a>';
				$btn .= '<a href="'.url('dashboard/menu-manager/'.Hashids::encode($menumanager->id).'/edit').'" class="btn btn-primary btn-xs btn-icon" title="'.__('general.edit').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i></a>';
				$btn .= '<a href="'.url('dashboard/menu-manager/'.Hashids::encode($menumanager->id).'').'" class="btn btn-danger btn-xs btn-icon" data-delete="" title="'.__('general.delete').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash"></i></a>';
				$btn .= '</div></div>';
				return $btn;
            })
			->addColumn('control', function ($menumanager) {
				$check = '<div style="text-align:center;"><a href="javascript:void(0);" class="btn btn-secondary btn-xs btn-icon" data-placement="left"><i class="fa fa-plus"></i></a></div>';
				return $check;
			})
			->escapeColumns([])
			->make(true);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		if(Auth::user()->can('create-menumanager')) {
			$tree = new Menu;
			$parents = $tree->tree()->toArray();
			
			return view('backend.menumanager.create', compact('parents'));
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
		if(Auth::user()->can('create-menumanager')) {
			$this->validate($request,[
				'title' => 'required',
				'url' => 'required',
				'target' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Menu::create($requestData);
			
			return redirect()->route('menumanager.index')->with('success', __('menumanager.store_notif'));
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
    public function show()
    {
		//
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
		if(Auth::user()->can('update-menumanager')) {
			$ids = Hashids::decode($id);
			$menumanager = Menu::findOrFail($ids[0]);
			$tree = new Menu;
			$parents = $tree->tree()->toArray();

			return view('admin.menumanager.edit', compact('menumanager', 'parents'));
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
		if(Auth::user()->can('update-menumanager')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required',
				'url' => 'required',
				'target' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$menumanager = Menu::findOrFail($ids[0]);
			$menumanager->update($requestData);

			return redirect()->route('menumanager.index')->with('success', __('menumanager.update_notif'));
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
    public function destroy($id)
    {
		if(Auth::user()->can('delete-menumanager')) {
			$ids = Hashids::decode($id);
			Menu::destroy($ids[0]);

			return redirect()->back()->with('success', __('menumanager.destroy_notif'));
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('delete-menumanager')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
        		Menu::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('menumanager.destroy_notif'));
			} else {
				return redirect()->route('menumanager.index')->with('error', __('menumanager.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
	
    public function menusort(Request $request)
    {
		if(Auth::user()->can('update-menumanager')) {
			$data = json_decode($request->input('data'));
			$readbleArray = $this->parseJsonArray($data);
			$i = 0;
			foreach($readbleArray as $row){
				$i++;
				$request->request->add([
					'parent' => $row['parentID'],
					'position' => $i,
					'updated_by' => Auth::User()->id
				]);
				$requestData = $request->all();

				$menu = Menu::findOrFail($row['id']);
				$menu->update($requestData);
			}
		} else {
			return redirect('forbidden');
		}
	}

	public function parseJsonArray($jsonArray, $parentID = 0) {
		$return = array();
		foreach ($jsonArray as $subArray) {
			$returnSubSubArray = array();
			if (isset($subArray->children)) {
				$returnSubSubArray = $this->parseJsonArray($subArray->children, $subArray->id);
			}
			$return[] = array('id' => $subArray->id, 'parentID' => $parentID);
			$return = array_merge($return, $returnSubSubArray);
		}
		return $return;
	}
}
