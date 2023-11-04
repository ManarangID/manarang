<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $themes = Theme::all();
        return response(view('components.themes.index', ['tags' => $tags]));
    }
}
