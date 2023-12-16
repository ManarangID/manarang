<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Album;
use App\Models\Gallery;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AlbumRequest;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-gallerys')) {
            $albums = Album::leftJoin('users', 'users.id', '=', 'albums.created_by')
			->select('albums.*', 'users.id as uid', 'users.name as uname')->get();
                return view('admin.album.datatable', compact('albums'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
		if(Auth::user()->can('create-gallerys')) {
			return view('admin.album.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-gallerys')) {
			$this->validate($request,[
				'title' => 'required',
				'seotitle' => 'required|string|unique:albums'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Album::create($requestData);
			
			return redirect()->route('albums.index')->with('success', __('album.store_notif'));
		} else {
			return redirect('401');
		}
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
		if(Auth::user()->can('update-gallerys')) {
			$ids = Hashids::decode($id);
			$albums = Album::findOrFail($ids[0]);
			$gallerys = Gallery::where('album_id', '=', $albums->id)->get();

			return view('admin.album.edit', compact('albums','gallerys'));
		} else {
			return abort('401');
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
		if(Auth::user()->can('update-gallerys')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required',
				'seotitle' => 'required|string|unique:albums,seotitle,' . $ids[0],
				'active' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$album = Album::findOrFail($ids[0]);
			$album->update($requestData);

			return redirect()->route('albums.index')->with('success', __('album.update_notif'));
		} else {
			return redirect('401');
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
		if(Auth::user()->can('delete-gallerys')) {
			$ids = Hashids::decode($id);
			Gallery::whereIn('album_id',$ids)->update(['title' => 'N/A']);
			// Gallery::whereIn('album_id',$ids)->delete();
			// Album::destroy($ids[0]);
			return redirect()->route('albums.index')->with('success', __('album.destroy_notif'));
		} else {
			return redirect('401');
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
		if(Auth::user()->can('delete-gallerys')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
                Gallery::whereIn('album_id',explode(",",$ids))->update(['title' => 'N/A']);
                Album::whereIn('id',explode(",",$ids))->delete();
				return redirect()->route('albums.index')->with('success', __('album.destroy_notif'));
			} else {
				return redirect()->route('albums.index')->with('error', __('album.destroy_error_notif'));
			}
		} else {
			return redirect('401');
		}
    }
	
	public function createGallery(Request $request)
    {
		if(Auth::user()->can('create-gallerys')) {
			$image = $request->file('file');
			$imageName = time().$image->getClientOriginalName();
			$filename = pathinfo($imageName, PATHINFO_FILENAME);
			$image->move(public_path('storage/gallery'),$imageName);
	
			$imageUpload = new Gallery();
			$imageUpload->album_id = $request->input('album_id');
			$imageUpload->title = $request->input('title');
			$imageUpload->picture = $imageName;
			$imageUpload->save();
			return response()->json(['success'=>$imageName]);
		}
    }
	
	public function deleteGallery(Request $request)
    {
		if(Auth::user()->can('delete-posts')) {
			$ids = Hashids::decode($request->id);
			$gallery = Gallery::findOrFail($ids[0]);
			Storage::delete('public/gallery/'.$gallery->picture);
			Gallery::destroy($ids[0]);
			
			$result = array(
				'code' => '2000',
				'message' => 'Success',
				'data' => []
			);
			
			return \Response::json($result);
		} else {
			$result = array(
				'code' => '4004',
				'message' => 'Error',
				'data' => []
			);
			
			return \Response::json($result);
		}
    }
}
