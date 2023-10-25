<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pages;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PagesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image as ResizeImage;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $pages = Pages::all();
        return response(view('components.pages.index', ['pages' => $pages]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response(view('components.pages.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PagesRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->picture != null){
            //upload image
            
            $path = public_path('storage/pages/');
            $pathThumbnail = public_path('storage/pages/thumbnail/');
            !is_dir($path) && mkdir($path, 0777, true);
            !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);

            $name = $request->picture->hashName();
            ResizeImage::make($request->file('picture'))
                ->resize(1280, 720)
                ->save($path . $name);

  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            ResizeImage::make($request->file('picture'))
            ->resize(48, 48)
            ->save($pathThumbnail . $name);

            $validated['picture'] = $name;
            $validated['created_by'] = auth()->user()->id;;
            $validated['updated_by'] = auth()->user()->id;;
        }
        Pages::create($validated); 
        
        return redirect(route('pages'))->with('success', 'Added!');
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $seotitle): View
    {
        $pages = Pages::whereSeotitle($seotitle)->first();
        if ($pages && $pages->active != 'N'){
            $users = User::findOrFail($pages->created_by);
            Pages::where('id', $pages->id)->increment('hits');
            return view('frontend.canvas.pages', ['pages' => $pages, 'users' => $users]);
        }else{
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $pages = Pages::findOrFail($id);
        return response(view('components.pages.edit', ['pages' => $pages]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'picture'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'seotitle'   => 'required|min:5'
        ]);

        //get pages by ID
        $pages = Pages::findOrFail($id);
        if ($request->hasFile('picture')) {
            //delete old image
            Storage::delete('public/pages/'.$pages->picture);
            Storage::delete('public/pages/thumbnail/'.$pages->picture);
            //upload image
            // $picture = $request->file('picture');
            // $picture->storeAs('public/images', $picture->hashName());
            
            $path = public_path('storage/pages/');
            $pathThumbnail = public_path('storage/pages/thumbnail/');
            !is_dir($path) && mkdir($path, 0777, true);
            !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);

            $name = $request->picture->hashName();
            ResizeImage::make($request->file('picture'))
                ->resize(1280, 720)
                ->save($path . $name);

  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            ResizeImage::make($request->file('picture'))
            ->resize(48, 48)
            ->save($pathThumbnail . $name);

            $pages->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'content'     => $request->content,
                'picture'     => $name,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }else{
            //update post without image
            $pages->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'content'     => $request->content,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }
        return redirect()->route('pages')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Likes generate.
     */
    public function likes(Request $request, string $id)
    {

        //get pages by ID
        $pages = Pages::findOrFail($id);
        //Increment likes value
        Pages::where('id', $id)->increment('likes');
        return back()->withFragment('#likes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $pages = Pages::findOrFail($id);

        Storage::delete('public/images/'.$pages->picture);
        if ($pages->delete()) {
            return redirect(route('pages'))->with('success', 'Deleted!');
        }

        return redirect(route('pages'))->with('error', 'Sorry, unable to delete this!');
    }
}
