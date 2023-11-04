<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Http\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = Categories::all();
        return response(view('components.categories.index', ['categories' => $categories]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response(view('components.categories.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriesRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->picture != null){
            //upload image
            $picture = $request->file('picture');
            $picture->storeAs('public/images', $picture->hashName());
            $validated['picture'] = $picture->hashName();
            $validated['created_by'] = auth()->user()->id;;
            $validated['updated_by'] = auth()->user()->id;;
        }
        Categories::create($validated); 
        
        return redirect(route('categories'))->with('success', 'Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($seotitle)
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
			SEOTools::opengraph()->addImage($categories->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($categories->picture, null, $categories->updated_by));
			SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
			SEOTools::twitter()->setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::twitter()->setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::twitter()->setUrl(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::twitter()->setImage($categories->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($categories->picture, null, $categories->updated_by));
			SEOTools::jsonLd()->setTitle($categories->title.' - '.getSetting('web_name'));
			SEOTools::jsonLd()->setDescription($categories->title.' - '.getSetting('web_description'));
			SEOTools::jsonLd()->setType('WebPage');
			SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/category/' . $categories->seotitle);
			SEOTools::jsonLd()->setImage($categories->picture == '' ? asset('po-content/uploads/'.getSetting('logo')) : getPicture($categories->picture, null, $categories->updated_by));
			
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
				SEOTools::opengraph()->addImage(asset('po-content/uploads/'.getSetting('logo')));
				SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
				SEOTools::twitter()->setTitle('All Category - '.getSetting('web_name'));
				SEOTools::twitter()->setDescription('All Category - '.getSetting('web_description'));
				SEOTools::twitter()->setUrl(getSetting('web_url') . '/category/all');
				SEOTools::twitter()->setImage(asset('po-content/uploads/'.getSetting('logo')));
				SEOTools::jsonLd()->setTitle('All Category - '.getSetting('web_name'));
				SEOTools::jsonLd()->setDescription('All Category - '.getSetting('web_description'));
				SEOTools::jsonLd()->setType('WebPage');
				SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/category/all');
				SEOTools::jsonLd()->setImage(asset('po-content/uploads/'.getSetting('logo')));
				
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $categories = Categories::findOrFail($id);
        return response(view('components.categories.edit', ['categories' => $categories]));
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

        //get categories by ID
        $categories = Categories::findOrFail($id);
        if ($request->hasFile('picture')) {
            //delete old image
            Storage::delete('public/images/'.$categories->picture);
            //upload image
            $picture = $request->file('picture');
            $picture->storeAs('public/images', $picture->hashName());


            $categories->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'picture'     => $picture->hashName(),
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }else{
            //update post without image
            $categories->update([
                'title'     => $request->title,
                'seotitle'     => $request->seotitle,
                'content'     => $request->content,
                'updated_by' => auth()->user()->id,
                'active'     => $request->active,
                'updated_at'     => Carbon::now()
            ]);
        }
        return redirect()->route('categories')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $categories = Categories::findOrFail($id);

        Storage::delete('public/images/'.$categories->picture);
        if ($categories->delete()) {
            return redirect(route('categories'))->with('success', 'Deleted!');
        }

        return redirect(route('categories'))->with('error', 'Sorry, unable to delete this!');
    }
}
