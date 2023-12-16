<?php

namespace App\Http\Controllers;

use Response;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-tags')) {
            $tags = Tag::leftJoin('users', 'users.id', '=', 'tags.created_by')
			->select('tags.*', 'users.id as uid', 'users.name as uname')->get();
			return view('admin.tags.datatable', compact('tags'));
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
		if(Auth::user()->can('create-tags')) {
			return view('admin.tags.create');
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
		if(Auth::user()->can('create-tags')) {
			$this->validate($request,[
				'title' => 'required'
			]);
			
			$expl = explode(',', $request->title);
			$total = count($expl);
			for($i=0; $i<$total; $i++){
				$checkTag = Tag::where('seotitle', '=', Str::slug($expl[$i], '-'))->count();
				if ($checkTag == 0) {
					Tag::create([
						'title' => $expl[$i],
						'seotitle' => Str::slug($expl[$i], '-'),
						'created_by' => Auth::User()->id,
						'updated_by' => Auth::User()->id
					]);
				}
			}
			
			return redirect()->route('tags.index')->with('success', __('tag.store_notif'));
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
    public function edit($id)
    {
		if(Auth::user()->can('update-tags')) {
			$ids = Hashids::decode($id);
			$tag = Tag::findOrFail($ids[0]);

			return view('admin.tags.edit', compact('tag'));
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
		if(Auth::user()->can('update-tags')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$tag = Tag::findOrFail($ids[0]);
			$tag->update($requestData);

			return redirect()->route('tags.index')->with('success', __('tag.update_notif'));
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
    public function destroy($id): RedirectResponse
    {
		if(Auth::user()->can('delete-tags')) {
			$ids = Hashids::decode($id);
			Tag::destroy($ids[0]);

			return redirect()->route('tags.index')->with('success', __('tag.destroy_notif'));
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
		if(Auth::user()->can('delete-tags')) {
			if ($request->has('id')) {
				$ids = $request->id;
				foreach($ids as $id){
					$idd = Hashids::decode($id);
					Tag::destroy($idd[0]);
				}
				return redirect()->route('tags.index')->with('success', __('tag.destroy_notif'));
			} else {
				return redirect()->route('tags.index')->with('error', __('tag.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
	
	public function getTag(Request $request): RedirectResponse
    {
		if(Auth::user()->can('read-tags')) {
			$tags = Tag::select('id', 'title', 'seotitle')->where('title', 'LIKE', '%'.$request->term.'%')->get();

			$result = array(
				'code' => '2000',
				'message' => 'Success',
				'data' => $tags
			);
			
			return \Response::json($result);
		} else {
			$result = array(
				'code' => '4004',
				'message' => 'Error',
				'data' => []
			);
			
			return Response::json($result);
		}
    }
}
