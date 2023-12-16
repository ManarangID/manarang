<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pages;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PagesRequest;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
		if(Auth::user()->can('read-pages')) {
            $pages = Pages::leftJoin('users', 'users.id', '=', 'pages.created_by')
                ->select('pages.*', 'users.id as uid', 'users.name as uname', 'users.profile_photo_path as profile')->get();
			return view('admin.pages.datatable', compact('pages'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
		if(Auth::user()->can('create-pages')) {
			return view('admin.pages.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-pages')) {
			$this->validate($request,[
				'title' => 'required',
				'seotitle' => 'required|string|unique:pages'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			//upload image
			if ($request->hasFile('picture')){
				$path = public_path('storage/pages/');
				$pathThumbnail = public_path('storage/pages/thumbnail/');
				!is_dir($path) && mkdir($path, 0777, true);
				!is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
				ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);
	
					$requestData['picture'] = "$name";
			}

			Pages::create($requestData);
			
			return redirect()->route('pages.index')->with('success', __('pages.store_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
		if(Auth::user()->can('update-pages')) {
			$ids = Hashids::decode($id);
			$pages = Pages::findOrFail($ids[0]);

			return view('admin.pages.edit', compact('pages'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
		if(Auth::user()->can('update-pages')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required',
				'seotitle' => 'required|string|unique:pages,seotitle,' . $ids[0],
				'active' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$pages = Pages::findOrFail($ids[0]);
			//upload image
			if ($request->hasFile('picture')){
				//delete old image
				Storage::delete('public/pages/'.$pages->picture);
				Storage::delete('public/pages/thumbnail/'.$pages->picture);
				$path = public_path('storage/pages/');
				$pathThumbnail = public_path('storage/pages/thumbnail/');
				!is_dir($path) && mkdir($path, 0777, true);
				!is_dir($pathThumbnail) && mkdir($pathThumbnail, 0777, true);
	
				$name = $request->picture->hashName();
				ResizeImage::make($request->file('picture'))->resize(1280, 720)->save($path . $name);
				ResizeImage::make($request->file('picture'))->resize(48, 48)->save($pathThumbnail . $name);
	
					$requestData['picture'] = "$name";
			}

			$pages->update($requestData);

			return redirect()->route('pages.index')->with('success', __('pages.update_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
		if(Auth::user()->can('delete-pages')) {
			$ids = Hashids::decode($id);
			if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
				Pages::destroy($ids[0]);
			} else {
				$pages = Pages::findOrFail($ids[0]);
				
				if ($pages->created_by == Auth::user()->id) {
					Pages::destroy($ids[0]);
				} else {
					return redirect('forbidden');
				}
			}

			return redirect()->route('pages.index')->with('success', __('pages.destroy_notif'));
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
		if(Auth::user()->can('delete-pages')) {
			if ($request->has('ids')) {
				$ids = $request->ids;
					if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
						Pages::whereIn('id',explode(",",$ids))->delete();
					} else {
						$pages = Pages::findOrFail($ids[0]);
						
						if ($pages->created_by == Auth::user()->id) {
							Pages::whereIn('id',explode(",",$ids))->delete();
						} else {
							return redirect('401');
						}
					}
				return redirect()->route('pages.index')->with('success', __('pages.destroy_notif'));
			} else {
				return redirect()->route('pages.index')->with('error', __('pages.destroy_error_notif'));
			}
		} else {
			return redirect('401');
		}
    }

    /**
     * Show the application pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($seotitle): Response
    {
		$pages = Pages::where([['seotitle', '=', $seotitle],['active', '=', 'Y']])->first();
		
		if($pages) {
			$twitterid = explode('/', getSetting('twitter'));
			SEOTools::setTitle($pages->title.' - '.getSetting('web_name'));
			SEOTools::setDescription(\Str::limit(strip_tags($pages->content), 200));
			SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
			SEOTools::setCanonical(getSetting('web_url') . '/pages/' . $pages->seotitle);
			SEOTools::opengraph()->setTitle($pages->title.' - '.getSetting('web_name'));
			SEOTools::opengraph()->setDescription(\Str::limit(strip_tags($pages->content), 200));
			SEOTools::opengraph()->setUrl(getSetting('web_url') . '/pages/' . $pages->seotitle);
			SEOTools::opengraph()->setSiteName(getSetting('web_author'));
			SEOTools::opengraph()->addImage($pages->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicturepages($pages->picture, null, $pages->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($pages->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription(\Str::limit(strip_tags($pages->content), 200));
			SEOTools::twitter()->setUrl(getSetting('web_url') . '/pages/' . $pages->seotitle);
			SEOTools::twitter()->setImage($pages->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicturepages($pages->picture, null, $pages->updated_by));
			SEOTools::jsonLd()->setTitle($pages->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription(\Str::limit(strip_tags($pages->content), 200));
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/pages/' . $pages->seotitle);
			SEOTools::jsonLd()->setImage($pages->picture == '' ? asset(Storage::url('images/'.getSetting('logo'))) : getPicturepages($pages->picture, null, $pages->updated_by));
			
            return response(view(getTheme('pages'), compact('pages')));
		} else {
			return redirect('404');
		}
	}
}
