<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $posts = Post::join('categories', 'posts.category_id', '=', 'categories.id')->select('posts.*', 'categories.title as categoryTitle')->orderBy('id','desc')->get();
        return response(view('components.posts.index', ['posts' => $posts]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categories = Categories::all();
        return response(view('components.posts.create', ['categories' => $categories]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->picture != null){
            //upload image
            $path = public_path('storage/post/');
            $pathThumbnail = public_path('storage/post/thumbnail/');
            !is_dir($path) && mkdir($path, 0777, true);
            !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);

            $name = $request->picture->hashName();
            ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);

            $validated['picture'] = $name;
            $validated['created_by'] = auth()->user()->id;;
            $validated['updated_by'] = auth()->user()->id;;
        }
        Post::create($validated); 
        
        return redirect(route('posts'))->with('success', 'Added!');
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
        $posts = Post::findOrFail($id);
        $categories = Categories::all();
        $category_id = Categories::where('id', '$post->category_id')->get();
        return response(view('components.posts.edit', ['posts' => $posts,'categories' => $categories]));
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

        //get posts by ID
        $posts = Post::findOrFail($id);
        if ($request->hasFile('picture')) {
            //delete old image
            Storage::delete('public/post/'.$posts->picture);
            Storage::delete('public/post/thumbnail/'.$posts->picture);
            //upload image
            // $picture = $request->file('picture');
            // $picture->storeAs('public/post', $picture->hashName());
            $path = public_path('storage/post/');
            $pathThumbnail = public_path('storage/post/thumbnail/');
            !is_dir($path) && mkdir($path, 0777, true);
            !is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);

            $name = $request->picture->hashName();
            ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
  
            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);

            $posts->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'content'     => $request->content,
                'meta_description'     => $request->meta_description,
                'picture'     => $name,
                'picture_description'     => $request->picture_description,
                'tag'     => $request->tag,
                'type'     => $request->type,
                'active'     => $request->active,
                'headline'     => $request->headline,
                'comment'     => $request->comment,
                'category_id'     => $request->category_id,
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }else{
            //update post without image
            $posts->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'content'     => $request->content,
                'meta_description'     => $request->meta_description,
                'picture_description'     => $request->picture_description,
                'tag'     => $request->tag,
                'type'     => $request->type,
                'active'     => $request->active,
                'headline'     => $request->headline,
                'comment'     => $request->comment,
                'category_id'     => $request->category_id,
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }
        return redirect()->route('posts')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Likes generate.
     */
    public function likes(Request $request, string $id)
    {

        //get pages by ID
        $posts = Post::findOrFail($id);
        //Increment likes value
        Post::where('id', $id)->increment('likes');
        return back()->withFragment('#likes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $posts = Post::findOrFail($id);

        Storage::delete('public/post/'.$posts->picture);
        if ($posts->delete()) {
            return redirect(route('posts'))->with('success', 'Deleted!');
        }

        return redirect(route('posts'))->with('error', 'Sorry, unable to delete this!');
    }
}
