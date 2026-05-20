<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('words')->inRandomOrder()->limit(9)->get();

        return view('home', compact('collections'));
    }
}
