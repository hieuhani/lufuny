<?php

namespace App\Transformer;

use App\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Media $media)
    {
        $author = null;
        if ($media->user !== null) {
            $author = [
                'id' => $media->user->id,
                'name' => $media->user()->name
            ];
        }
        $category = null;
        if ($media->category !== null) {
            $category = [
                'id' => $media->category->id,
                'name' => $media->category->name
            ];
        }
        $imageFolder = env('IMAGE_FOLDER', 'uploads/images/');
        return [
            'id' => $media->id,
            'description' => $media->description,
            'nickname' => $media->nickname,
            'media_url' => $imageFolder . $media->media_url,
            'active' => $media->active,
            'type' => $media->type,
            'author' => $author,
            'category' => $category
        ];
    }
}