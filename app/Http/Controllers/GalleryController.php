<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Album;
use App\Models\Gallery;
use Illuminate\Http\File;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-gallerys')) {
            $gallerys = Gallery::leftJoin('albums', 'albums.id', '=', 'gallerys.album_id')
                ->leftJoin('users', 'users.id', '=', 'gallerys.created_by')
                ->select('gallerys.*', 'albums.id as aid', 'albums.title as atitle', 'users.id as uid', 'users.name as uname')->get();
			return view('admin.gallery.datatable', compact('gallerys'));
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
			$albums = Album::where('active', '=', 'Y')->limit(10)->get();			
			return view('admin.gallery.datatable', compact('albums'));
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
				'album_id' => 'required',
				'title' => 'required',
				'picture' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();
            if ($request->hasFile('picture')){
                //upload image
    
                $path = public_path('storage/gallery/');
                $pathThumbnail = public_path('storage/gallery/thumbs/');
                !is_dir($path) && mkdir($path, 0777, true);
                !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
                
                if ($request->album_id == 1) {
                    $name = $request->picture->hashName();
                    ResizeImage::make($request->file('picture'))
                        ->resize(2000, 1329)
                        ->save($path . $name);
                }else{
                    $name = $request->picture->hashName();
                    ResizeImage::make($request->file('picture'))
                        ->resize(1200, 1082)
                        ->save($path . $name);
                }
                
                /**
                 * Generate Thumbnail Image Upload on Folder Code
                 */
                ResizeImage::make($request->file('picture'))
                ->resize(400, 300)
                ->save($pathThumbnail . $name);
    
                $requestData['picture'] = "$name";
            }

			Gallery::create($requestData);
			
			return redirect()->route('gallerys.index')->with('success', __('gallery.store_notif'));
		} else {
			return abort('401');
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
			$gallery = Gallery::findOrFail($ids[0]);
			$albums = Album::where('active', '=', 'Y')->limit(10)->get();

			return view('admin.gallery.datatable', compact('gallery', 'albums'));
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
				'album_id' => 'required',
				'title' => 'required',
				'picture' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$gallery = Gallery::findOrFail($ids[0]);
			$gallery->update($requestData);

			return redirect()->route('gallerys.index')->with('success', __('gallery.update_notif'));
		} else {
			return abort('401');
		}
        //validate form
        $this->validate($request, [
            'picture'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5'
        ]);

        //get posts by ID
        $gallerys = Gallery::findOrFail($id);
        if ($request->hasFile('picture')) {
            //delete old image
            Storage::delete('public/gallery/'.$gallerys->picture);
            Storage::delete('public/gallery/thumbs/'.$gallerys->picture);

            $path = public_path('storage/gallery/');
            $pathThumbnail = public_path('storage/gallery/thumbs/');
            !is_dir($path) && mkdir($path, 0777, true);
            !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
            
            if ($request->album_id == 1) {
                $name = $request->picture->hashName();
                ResizeImage::make($request->file('picture'))
                    ->resize(2000, 1329)
                    ->save($path . $name);
            }else{
                $name = $request->picture->hashName();
                ResizeImage::make($request->file('picture'))
                    ->resize(1200, 1082)
                    ->save($path . $name);
            }

            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            ResizeImage::make($request->file('picture'))
            ->resize(400, 300)
            ->save($pathThumbnail . $name);

            $gallerys->update([
                'title'     => $request->title,
                'content'     => $request->content,
                'picture'     => $name,
                'album_id'     => $request->album_id,
                'updated_by' => auth()->user()->id,
                'updated_at'     => Carbon::now()
            ]);
        }else{
            //update gallerys without image
            $gallerys->update([
                'title'     => $request->title,
                'content'     => $request->content,
                'album_id'     => $request->album_id,
                'updated_by' => auth()->user()->id,
                'updated_at'     => Carbon::now()
            ]);
        }
        return redirect()->route('gallerys')->with(['success' => 'Data Berhasil Diubah!']);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
		if(Auth::user()->can('delete-gallerys')) {
			$ids = Hashids::decode($id);
			$gallery = Gallery::findOrFail($ids[0]);
			Storage::delete('public/gallery/thumbs/'.$gallery->picture);
			Storage::delete('public/gallery/'.$gallery->picture);
			$gallery->delete();
			return redirect()->route('gallerys.index')->with('success', __('gallery.destroy_notif'));
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
		if(Auth::user()->can('delete-categories')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
                $vehicles = Gallery::find($ids);
        		$gallery = Gallery::whereIn('id',explode(",",$ids));
                $gallery->delete();
                $images = explode(",", $vehicles->picture);
                foreach ($images as $image) {
                    Storage::delete("public/gallery/{$image}");
                }
                 return response()->json(['success'=>"Products Deleted successfully."]);
			} else {
				return redirect('categories.index')->with('success', __('categories.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
}
