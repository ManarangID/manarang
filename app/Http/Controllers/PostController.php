<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Categories;
use App\Models\PostGallery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\RedirectResponse;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * Display the specified resource.
     */
    public function show($seotitle, Request $request): Response
    {
        if(getSetting('slug') == 'post/slug-id') {
			$expseotitle = explode('-', $seotitle);
			array_pop($expseotitle);
			$seotitle = implode('-', $expseotitle);
		} else {
			$seotitle = $seotitle;
		}
		
		$checkpost = Post::where([['seotitle', '=', $seotitle],['active', '=', 'Y']])->first();
		
		if($checkpost) {
			$checkpost->update([
				'hits' => DB::raw('hits+1')
			]);
			
			$post = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.seotitle', '=', $seotitle],['posts.active', '=', 'Y']])
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name', 'users.profile_photo_path as photo', 'users.bio as bio')
				->orderBy('posts.id', 'desc')
				->withCount('comments')
				->first();
			
			$gallery = PostGallery::where('post_id', '=', $post->id)->get();
			
			$segment = isset($request->segment) ? $request->segment : 1;
			$expcontent = explode('<hr />', $post->content);
			$paginator = $this->customPaginate($expcontent, 1, $segment, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'segment'
			]);
			$content = '';
			if($post->type == 'pagination') {
				if(count($expcontent) > 0) {
					$content = $expcontent[$segment-1];
				} else {
					$content = $post->content;
				}
			} else {
				$content = $post->content;
			}
			
			$seturl = '';
			if(getSetting('slug') == 'post/slug-id') {
				$seturl = '/detailpost/' . $post->seotitle . '-' . $post->id;
			} else {
				$seturl = '/detailpost/' . $post->seotitle;
			}
			
			$twitterid = explode('/', getSetting('twitter'));
			SEOTools::setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::setDescription($post->meta_description);
			SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
			SEOTools::setCanonical(getSetting('web_url') . $seturl);
			SEOTools::opengraph()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::opengraph()->setDescription($post->meta_description);
			SEOTools::opengraph()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::opengraph()->setSiteName(getSetting('web_author'));
			SEOTools::opengraph()->addImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($post->meta_description);
			SEOTools::twitter()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::twitter()->setImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::jsonLd()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($post->meta_description);
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::jsonLd()->setImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			
			return response(view(getTheme('detailpost'), compact('post', 'content', 'paginator', 'gallery')));
		} else {
			return redirect('404');
		}
    }
	
	/**
     * Show the application post.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function article($year, $month, $day, $seotitle, Request $request)
    {
		$checkpost = Post::where([['seotitle', '=', $seotitle],['active', '=', 'Y']])->first();
		
		if($checkpost) {
			$checkpost->update([
				'hits' => DB::raw('hits+1')
			]);
			
			$post = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.seotitle', '=', $seotitle],['posts.active', '=', 'Y']])
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->orderBy('posts.id', 'desc')
				->withCount('comments')
				->first();
			
			$gallery = PostGallery::where('post_id', '=', $post->id)->get();
			
			$segment = isset($request->segment) ? $request->segment : 1;
			$expcontent = explode('<hr />', $post->content);
			$paginator = $this->customPaginate($expcontent, 1, $segment, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'segment'
			]);
			$content = '';
			if($post->type == 'pagination') {
				if(count($expcontent) > 0) {
					$content = $expcontent[$segment-1];
				} else {
					$content = $post->content;
				}
			} else {
				$content = $post->content;
			}
			
			$seturl = '/article/' . $year . '/' . $month . '/' . $day . '/' . $post->seotitle;
			
			$twitterid = explode('/', getSetting('twitter'));
			SEOTools::setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::setDescription($post->meta_description);
			SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
			SEOTools::setCanonical(getSetting('web_url') . $seturl);
			SEOTools::opengraph()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::opengraph()->setDescription($post->meta_description);
			SEOTools::opengraph()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::opengraph()->setSiteName(getSetting('web_author'));
			SEOTools::opengraph()->addImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($post->meta_description);
			SEOTools::twitter()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::twitter()->setImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::jsonLd()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($post->meta_description);
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::jsonLd()->setImage($post->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($post->picture, null, $post->updated_by));
			
			return view(getTheme('detailpost'), compact('post', 'content', 'paginator', 'gallery'));
		} else {
			return redirect('404');
		}
	}
	
	/**
     * Show the application search post.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function search(Request $request)
    {
		$validator = $this->validate($request,[
			'terms' => 'required|max:255',
		]);
		
		$terms = strip_tags($request->terms);
		
		$twitterid = explode('/', getSetting('twitter'));
		SEOTools::setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
		SEOTools::setCanonical(getSetting('web_url') . '/search');
		SEOTools::opengraph()->setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::opengraph()->setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::opengraph()->setUrl(getSetting('web_url') . '/search');
		SEOTools::opengraph()->setSiteName(getSetting('web_author'));
		SEOTools::opengraph()->addImage(asset('po-content/uploads/'.getSetting('logo')));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::twitter()->setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url') . '/search');
		SEOTools::twitter()->setImage(asset('po-content/uploads/'.getSetting('logo')));
		SEOTools::jsonLd()->setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::jsonLd()->setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/search');
		SEOTools::jsonLd()->setImage(asset('po-content/uploads/'.getSetting('logo')));
		
		$posts = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([
				['posts.title', 'LIKE', '%'.$terms.'%'],
				['posts.active', '=', 'Y']
			])
			->orWhere([
				['posts.content', 'LIKE', '%'.$terms.'%'],
				['posts.tag', 'LIKE', '%'.$terms.'%']
			])
			->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->paginate(5);
		
		$posts->appends(['terms' => $terms]);
		
		return view(getTheme('search'), compact('terms', 'posts'));
    }
	
	public function send($seotitle, Request $request)
    {
		$validator = Validator::make($request->all(), [
			'parent' => 'required',
			'post_id' => 'required',
			'name' => 'required|string|min:3',
			'email' => 'required|string|max:255|email',
			'content' => 'required|string|min:25',
			'g-recaptcha-response' => 'required|captcha'
		]);
		
		if($validator->fails()) {
			return redirect('detailpost/'.$seotitle.'#comment-form')
				->withErrors($validator)
				->withInput();
		}
		
		$request->request->add([
			'created_by' => 1,
			'updated_by' => 1
		]);
		$requestData = $request->all();
		
		Comment::create($requestData);
		
		return redirect('detailpost/'.$seotitle.'#comment-form')->with('flash_message', __('comment.send_notif'));
    }
	
	public static function customPaginate($items, $perPage = 1, $page = null, $options = [])
	{

		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

		$items = $items instanceof Collection ? $items : Collection::make($items);

		$lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

		return [
			'current_page' => $lap->currentPage(),
			'data' => $lap ->values(),
			'first_page_url' => $lap ->url(1),
			'from' => $lap->firstItem(),
			'last_page' => $lap->lastPage(),
			'last_page_url' => $lap->url($lap->lastPage()),
			'next_page_url' => $lap->nextPageUrl(),
			'per_page' => $lap->perPage(),
			'prev_page_url' => $lap->previousPageUrl(),
			'to' => $lap->lastItem(),
			'total' => $lap->total(),
		];
	}
}
