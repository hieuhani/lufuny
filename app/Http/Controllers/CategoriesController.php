<?php

namespace App\Http\Controllers;

use App\Category;
use App\Transformer\CategoryTransformer;

class CategoriesController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return $this->collection($categories, new CategoryTransformer());
    }
}
