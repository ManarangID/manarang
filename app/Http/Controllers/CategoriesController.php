<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\View\View;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoriesRequest;
use Intervention\Image\Facades\Image as ResizeImage;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-categories')) {
            $categorys = Categories::leftJoin('users', 'users.id', '=', 'categories.created_by')
                ->select('categories.*', 'users.id as uid', 'users.name as uname')->get();
			return view('admin.categories.datatable', compact('categorys'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
		if(Auth::user()->can('create-categories')) {
			$tree = new Categories;
			$parents = $tree->tree()->toArray();
			
			return view('admin.categories.create', compact('parents'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if(Auth::user()->can('create-categories')) {
			$this->validate($request,[
				'parent' => 'required',
				'title' => 'required',
				'seotitle' => 'required|string|unique:categories'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			if ($request->hasFile('picture')){
				//upload image
				
				$path = public_path('storage/post/category/');
				!is_dir($path) && mkdir($path, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))
					->resize(300, 300)
					->save($path . $name);
	
					$requestData['picture'] = "$name";
			}

			Categories::create($requestData);
			
			return redirect(route('categories.index'))->with('success', __('category.store_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        if(Auth::user()->can('update-categories')) {
			$ids = Hashids::decode($id);
			$category = Categories::findOrFail($ids[0]);
			$tree = new Categories;
			$parents = $tree->tree()->toArray();

			return view('admin.categories.edit', compact('category', 'parents'));
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
		if(Auth::user()->can('update-categories')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'parent' => 'required',
				'title' => 'required',
				'seotitle' => 'required|string|unique:categories,seotitle,' . $ids[0],
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$category = Categories::findOrFail($ids[0]);
			$category->update($requestData);
			
			if ($request->hasFile('picture')) {
				//delete old image
				Storage::delete('public/post/category/'.$category->picture);
				
				$path = public_path('storage/post/category/');
				!is_dir($path) && mkdir($path, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))
					->resize(300, 300)
					->save($path . $name);
				

				$category->update([
					'picture' => $name
				]);
			}

			return redirect()->route('categories.index')->with('success', __('category.update_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Display the specified resource.
     */
    public function detail($seotitle)
    {
        $categories = Categories::where([['seotitle', '=', $seotitle],['active', '=', 'Y']])->first();
		
		if($categories) {
			$twitterid = explode('/', getSetting('twitter'));
			SEOTools::setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
			SEOTools::setCanonical(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::opengraph()->setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::opengraph()->setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::opengraph()->setUrl(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::opengraph()->setSiteName(getSetting('web_author'));
			SEOTools::opengraph()->addImage($categories->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($categories->picture, null, $categories->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::twitter()->setUrl(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::twitter()->setImage($categories->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($categories->picture, null, $categories->updated_by));
			SEOTools::jsonLd()->setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::jsonLd()->setImage($categories->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicture($categories->picture, null, $categories->updated_by));
			
			$posts = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.category_id', '=', $categories->id],['posts.active', '=', 'Y']])
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->orderBy('posts.id', 'desc')
				->paginate(5);
			
			return view(getTheme('category'), compact('categories', 'posts'));
		} else {
			if($seotitle == 'all') {
				$twitterid = explode('/', getSetting('twitter'));
				SEOTools::setTitle('All Category - '.getSetting('web_name'));
				SEOTools::setDescription('All Category - '.getSetting('web_description'));
				SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
				SEOTools::setCanonical(getSetting('web_url') . '/category/all');
				SEOTools::opengraph()->setTitle('All Category - '.getSetting('web_name'));
				SEOTools::opengraph()->setDescription('All Category - '.getSetting('web_description'));
				SEOTools::opengraph()->setUrl(getSetting('web_url') . '/category/all');
				SEOTools::opengraph()->setSiteName(getSetting('web_author'));
				SEOTools::opengraph()->addImage(asset(Storage::url('images/'.getSetting('logo'))));
				SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
				SEOTools::twitter()->setTitle('All Category - '.getSetting('web_name'));
				SEOTools::twitter()->setDescription('All Category - '.getSetting('web_description'));
				SEOTools::twitter()->setUrl(getSetting('web_url') . '/category/all');
				SEOTools::twitter()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
				SEOTools::jsonLd()->setTitle('All Category - '.getSetting('web_name'));
				SEOTools::jsonLd()->setDescription('All Category - '.getSetting('web_description'));
				SEOTools::jsonLd()->setType('WebPage');
				SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/category/all');
				SEOTools::jsonLd()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
				
				$posts = Post::leftJoin('users', 'users.id', 'posts.created_by')
					->leftJoin('categories', 'categories.id', 'posts.category_id')
					->where([['posts.active', '=', 'Y']])
					->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
					->orderBy('posts.id', 'desc')
					->paginate(5);
				
				return view(getTheme('category'), compact('posts'));
			} else {
				return redirect('404');
			}
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
		if(Auth::user()->can('delete-categories')) {
			$ids = Hashids::decode($id);
			$category = Categories::findOrFail($ids[0]);
			//Storage::delete('public/post/category/'.$category->picture);
			// delete images
			if ($category->picture != null){
				Storage::delete('public/post/category/'.$category->picture);
			}
			Categories::destroy($ids[0]);
			return redirect()->route('categories.index')->with('success', __('category.destroy_notif'));
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
		if(Auth::user()->can('delete-categories')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
				$category = Categories::findOrFail($ids[0]);
        		Categories::whereIn('id',explode(",",$ids))->delete();
				// delete images
				if ($category->picture != null){
					Storage::delete('public/post/category/'.$category->picture);
				}
				return redirect()->back()->with('success', __('categories.destroy_notif'));
			} else {
				return redirect('categories.index')->with('success', __('categories.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
}
