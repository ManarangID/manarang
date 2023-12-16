<?php

namespace App\Http\Controllers;

use Mail;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Mail\Websitemail;
use Illuminate\View\View;
use App\Models\Categories;
use App\Models\Subscriber;
use App\Models\PostGallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		config([
			'captcha.secret' => getSetting('recaptcha_secret'),
			'captcha.sitekey' => getSetting('recaptcha_key'),
		]);
		
        // $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-posts')) {
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				$posts = Post::leftJoin('categories', 'categories.id', '=', 'posts.category_id')
					->leftJoin('users', 'users.id', '=', 'posts.created_by')
					->select('posts.*', 'categories.id as cid', 'categories.title as ctitle', 'users.id as uid', 'users.name as uname', 'users.profile_photo_path as profile')->get();
			} else {
				$posts = Post::leftJoin('categories', 'categories.id', '=', 'posts.category_id')
					->leftJoin('users', 'users.id', '=', 'posts.created_by')
					->where('posts.created_by', '=', Auth::user()->id)
					->select('posts.*', 'categories.id as cid', 'categories.title as ctitle', 'users.id as uid', 'users.name as uname', 'users.profile_photo_path as profile')->get();
			}
			return view('admin.posts.datatable', compact('posts'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
		if(Auth::user()->can('create-posts')) {
			$categorys = Categories::where('active', '=', 'Y')->get()->toArray();
			
			return view('admin.posts.create', compact('categorys'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): RedirectResponse
    {
		if(Auth::user()->can('create-posts')) {
			$this->validate($request,[
				'category_id' => 'required',
				'title' => 'required',
				'seotitle' => 'required|string|unique:posts',
				'type' => 'required',
				'active' => 'required',
				'headline' => 'required',
				'comment' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			//upload image
			if ($request->hasFile('picture')){
				$path = public_path('storage/post/');
				$pathThumbnail = public_path('storage/post/thumbnail/');
				!is_dir($path) && mkdir($path, 0777, true);
				!is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
				ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);
	
					$requestData['picture'] = "$name";
			}
			Post::create($requestData);
			
			$breaktags = explode(',', $request->tag);
			$totaltags = count($breaktags);
			if ($totaltags > 0) {
				for($i=0; $i<$totaltags; $i++){
					$checktag = Tag::where('seotitle', '=', Str::slug($breaktags[$i], '-'))->count();
					if($checktag > 0) {
						Tag::where('seotitle', '=', Str::slug($breaktags[$i], '-'))->update([
							'count' => DB::raw('count+1'),
							'updated_by' => Auth::User()->id
						]);
					} else {
						Tag::create([
							'title' => $breaktags[$i],
							'seotitle' => Str::slug($breaktags[$i], '-'),
							'created_by' => Auth::User()->id,
							'updated_by' => Auth::User()->id
						]);
					}
				}
			}
			
			return redirect()->route('posts.index')->with('success', __('post.store_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
		if(Auth::user()->can('update-posts')) {
			$ids = Hashids::decode($id);
			$post = Post::findOrFail($ids[0]);
			$post_gallerys = PostGallery::where('post_id', '=', $post->id)->get();
			$categorys = Categories::where('active', '=', 'Y')->get()->toArray();
			
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				return view('admin.posts.edit', compact('post', 'post_gallerys' ,'categorys'));
			} else {
				if ($post->created_by == Auth::user()->id) {
					return view('admin.posts.edit', compact('post', 'post_gallerys' ,'categorys'));
				} else {
					return redirect('forbidden');
				}
			}
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
		if(Auth::user()->can('update-posts')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'category_id' => 'required',
				'title' => 'required',
				'seotitle' => 'required|string|unique:posts,seotitle,' . $ids[0],
				'type' => 'required',
				'active' => 'required',
				'headline' => 'required',
				'comment' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$post = Post::findOrFail($ids[0]);

			//upload image
			if ($request->hasFile('picture')){
				//delete old image
				Storage::delete('public/post/'.$post->picture);
				Storage::delete('public/post/thumbnail/'.$post->picture);
				$path = public_path('storage/post/');
				$pathThumbnail = public_path('storage/post/thumbnail/');
				!is_dir($path) && mkdir($path, 0777, true);
				!is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
				ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);
	
					$requestData['picture'] = "$name";
			}
			$post->update($requestData);
			
			$breaktags = explode(',', $request->tag);
			$totaltags = count($breaktags);
			if ($totaltags > 0) {
				for($i=0; $i<$totaltags; $i++){
					$checktag = Tag::where('seotitle', '=', Str::slug($breaktags[$i], '-'))->count();
					if($checktag > 0) {
						Tag::where('seotitle', '=', Str::slug($breaktags[$i], '-'))->update([
							'count' => DB::raw('count+1'),
							'updated_by' => Auth::User()->id
						]);
					} else {
						Tag::create([
							'title' => $breaktags[$i],
							'seotitle' => Str::slug($breaktags[$i], '-'),
							'created_by' => Auth::User()->id,
							'updated_by' => Auth::User()->id
						]);
					}
				}
			}

			return redirect()->route('posts.index')->with('success', __('post.update_notif'));
		} else {
			return redirect('forbidden');
		}
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
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id): RedirectResponse
    {
		if(Auth::user()->can('delete-posts')) {
			$ids = Hashids::decode($id);
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				Post::destroy($ids[0]);
			} else {
				$post = Post::findOrFail($ids[0]);
				
				if ($post->created_by == Auth::user()->id) {
					Post::destroy($ids[0]);
				} else {
					return redirect('forbidden');
				}
			}

			return redirect()->route('posts.index')->with('success', __('post.destroy_notif'));
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
		if(Auth::user()->can('delete-posts')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
					if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
						Post::whereIn('id',explode(",",$ids))->delete();
					} else {
						$post = Post::findOrFail($ids[0]);
						
						if ($post->created_by == Auth::user()->id) {
							Post::whereIn('id',explode(",",$ids))->delete();
						} else {
							return redirect('forbidden');
						}
					}
				return redirect()->route('posts.index')->with('success', __('post.destroy_notif'));
			} else {
				return redirect()->route('posts.index')->with('error', __('post.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
	
	public function createGallery(Request $request)
    {
		if(Auth::user()->can('create-posts')) {
			$image = $request->file('file');
			$imageName = time().$image->getClientOriginalName();
			$filename = pathinfo($imageName, PATHINFO_FILENAME);
			$image->move(public_path('storage/post/gallery'),$imageName);
	
			$imageUpload = new PostGallery();
			$imageUpload->post_id = $request->input('post_id');
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
			$gallery = PostGallery::findOrFail($ids[0]);
			Storage::delete('public/post/gallery/'.$gallery->picture);
			PostGallery::destroy($ids[0]);
			
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

    /**
     * Send new post to subscriber
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
				*Post::findOrFail($idd[0])->update(['subscribe'=> "Y"]);
     */
    public function sendSubscriber(string $id): RedirectResponse
    {
		if(Auth::user()->can('read-posts')) {
			$idd = Hashids::decode($id);
			$posts = Post::findOrFail($idd[0]);
			if ($posts->active=="N"){
				return redirect()->back()->with('error', __('subscribe.send_new_post_error'));
			}else{
				$posts->subscribe = "Y";
				$posts->save();
				$subject = $posts->title;
				$message = '<a href="'.config('app.url').'/posts/'.$posts->seotitle.'">'.$posts->title.'</a>'.'<br>'.
							'<img src="'.config('app.url').'/storage/posts/'.$posts->picture.'"/>'.'<br>'
							.$posts->content;
	
				$subscribers = Subscriber::where('status','Active')->get();
				foreach($subscribers as $row) {
					Mail::to($row->email)->send(new Websitemail($subject,$message));
				}
	
				return redirect()->back()->with('success', __('subscribe.send_new_post'));
			}
		} else {
			return redirect()->route('posts.index')->with('error', __('subscribe.send_new_post_error'));
		}
    }

    /**
     * Display the specified resource.
     */
    public function detail($seotitle, Request $request): Response
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
			SEOTools::opengraph()->addImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($post->meta_description);
			SEOTools::twitter()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::twitter()->setImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::jsonLd()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($post->meta_description);
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::jsonLd()->setImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			
			return response(view(getTheme('detailpost'), compact('post', 'content', 'paginator', 'gallery')));
		} else {
			return abort('404');
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
			SEOTools::opengraph()->addImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($post->meta_description);
			SEOTools::twitter()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::twitter()->setImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			SEOTools::jsonLd()->setTitle($post->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($post->meta_description);
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . $seturl);
			SEOTools::jsonLd()->setImage($post->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($post->picture, null, $post->updated_by));
			
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
		SEOTools::opengraph()->addImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::twitter()->setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url') . '/search');
		SEOTools::twitter()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::jsonLd()->setTitle($terms.' - '.getSetting('web_name'));
		SEOTools::jsonLd()->setDescription($terms.' - '.getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/search');
		SEOTools::jsonLd()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		
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
