<?php

namespace App\Http\Controllers;

use App\Category;

class CategoriesController extends Controller
{

    public function index()
    {
        return Category::all();
    }
}
