<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $collections = Collection::limit(3)->get(); // Например, берем 6 подборок

        return view('home', compact('collections'));
    }
}
