<?php

namespace App\Transformer;

use App\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
      'files'
    ];

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

        return [
            'id' => $media->id,
            'description' => $media->description,
            'nickname' => $media->nickname,
            'active' => $media->active,
            'type' => $media->type,
            'author' => $author,
            'category' => $category,
            'total_votes' => $media->totalVotes(),
        ];
    }

    public function includeFiles(Media $media)
    {
        var_dump($media->files);
        return $this->collection($media->files, new FileTransformer());
    }
}