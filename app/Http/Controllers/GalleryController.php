<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Album;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $gallerys = Gallery::join('albums', 'galleries.album_id', '=', 'albums.id')->get(['galleries.*', 'albums.title as name']);
        return response(view('components.gallerys.index', ['gallerys' => $gallerys]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $albums = Album::where('active','Y')->get();
        return response(view('components.gallerys.create', ['albums' => $albums]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->picture != null){
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

            $validated['picture'] = $name;
            $validated['created_by'] = auth()->user()->id;;
            $validated['updated_by'] = auth()->user()->id;;
        }
        Gallery::create($validated); 
        
        return redirect(route('gallerys'))->with('success', 'Added!');
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
    public function edit(string $id): Response
    {
        $gallerys = Gallery::findOrFail($id);
        $albums = Album::where('active','Y')->get();
        $album_id = Album::where('id', '$gallerys->album_id')->get();
        return response(view('components.gallerys.edit', ['gallerys' => $gallerys,'albums' => $albums]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
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
        $gallerys = Gallery::findOrFail($id);

        Storage::delete('public/gallery/'.$gallerys->picture);
        if ($gallerys->delete()) {
            return redirect(route('gallerys'))->with('success', 'Deleted!');
        }

        return redirect(route('gallerys'))->with('error', 'Sorry, unable to delete this!');
    }
}
