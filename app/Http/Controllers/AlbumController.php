<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AlbumRequest;
use Illuminate\Http\RedirectResponse;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $albums = Album::all();
        return response(view('components.albums.index', ['albums' => $albums]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response(view('components.albums.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Album::create($validated); 
        
        return redirect(route('albums'))->with('success', 'Added!');
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
        $albums = Album::findOrFail($id);
        return response(view('components.albums.edit', ['albums' => $albums]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlbumRequest $request, string $id): RedirectResponse
    {
        //validate form
        $validated = $request->validated();

        //get pages by ID
        $albums = Album::findOrFail($id);
        $albums->update([
            'title'     => $request->title,
            'seotitle'     => $request->seotitle,
            'updated_by' => auth()->user()->id,
            'active'     => $request->active,
            'updated_at'     => Carbon::now()
        ]);

        return redirect()->route('albums')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $albums = Album::findOrFail($id);
        if ($albums->delete()) {
            return redirect(route('albums'))->with('success', 'Deleted!');
        }

        return redirect(route('albums'))->with('error', 'Sorry, unable to delete this!');
    }
}
