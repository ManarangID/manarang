<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Pages;
use App\Models\Gallery;
use Illuminate\View\View;
use App\Models\Categories;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(): View
    {
        $twitterid = explode('/', getSetting('twitter'));
		SEOTools::setTitle(getSetting('web_name'));
		SEOTools::setDescription(getSetting('web_description'));
		SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
		SEOTools::setCanonical(getSetting('web_url'));
		SEOTools::opengraph()->setTitle(getSetting('web_name'));
		SEOTools::opengraph()->setDescription(getSetting('web_description'));
		SEOTools::opengraph()->setUrl(getSetting('web_url'));
		SEOTools::opengraph()->setSiteName(getSetting('web_author'));
		SEOTools::opengraph()->addImage(asset('images/'.getSetting('logo')));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle(getSetting('web_name'));
		SEOTools::twitter()->setDescription(getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url'));
		SEOTools::twitter()->setImage(asset('po-content/uploads/'.getSetting('logo')));
		SEOTools::jsonLd()->setTitle(getSetting('web_name'));
		SEOTools::jsonLd()->setDescription(getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url'));
		SEOTools::jsonLd()->setImage(asset('images/'.getSetting('logo')));
		
        $sliders = Gallery::where('album_id',1)->take(5)->get();
        $categoriesLast = Post::join('categories', 'posts.category_id', '=', 'categories.id')->join('users', 'posts.created_by', '=', 'users.id')->select('posts.*', 'categories.title as categoryTitle')->where('posts.active', 'Y')->where('category_id',1)->orderBy('created_at', 'desc')->first();
        $categories = Post::where('category_id',$categoriesLast->category_id)->where('active', 'Y')->where('id','<>', $categoriesLast->id)->take(6)->orderBy('created_at', 'desc')->get();
        
        $category2 = Post::join('categories', 'posts.category_id', '=', 'categories.id')->join('users', 'posts.created_by', '=', 'users.id')->select('posts.*', 'users.name as editor', 'categories.title as categoryTitle')->where('posts.active', 'Y')->where('category_id',2)->orderBy('created_at', 'desc')->first();
        $categories2 = Post::where('category_id',$category2->category_id)->where('active', 'Y')->where('id','<>', $category2->id)->take(6)->orderBy('created_at', 'desc')->get();
        
        $popular = Post::orderBy('hits','DESC')->where('active', 'Y')->take(8)->get();
        $pages = Pages::where('active','Y')->take(5)->get();
        return view('frontend.canvas.home', ['sliders' => $sliders, 'categories' => $categories, 'categoriesLast' => $categoriesLast, 
                                            'category2' => $category2, 'categories2' => $categories2, 'popular' => $popular, 'pages' => $pages]);
    }
}
