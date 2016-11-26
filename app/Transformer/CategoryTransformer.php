<?php

namespace App\Transformer;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{


    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'description' => $category->description,
            'name' => $category->name,
        ];
    }
}