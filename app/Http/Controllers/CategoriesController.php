<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
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
    public function show(Categories $categories)
    {
        //
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
