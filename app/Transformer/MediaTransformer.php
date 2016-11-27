<?php

namespace App\Transformer;

use App\Media;
use Firebase\JWT\JWT;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{

    private $includedToken = false;

    public function __construct($includedToken = false)
    {
        $this->includedToken = $includedToken;
    }

    protected $availableIncludes = [
      'files'
    ];

    protected $defaultIncludes = [
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
        $categoryID = null;
        if ($media->category !== null) {
            $categoryID = $media->category->id;
        }

        $responseMessage = [
            'id' => $media->id,
            'description' => $media->description,
            'nickname' => $media->nickname,
            'active' => $media->active,
            'author' => $author,
            'category_id' => $categoryID,
            'total_votes' => $media->totalVotes(),
        ];

        if ($this->includedToken) {
            $responseMessage['media_token'] = $this->generateMediaToken($media);
        }

        return $responseMessage;
    }

    public function includeFiles(Media $media)
    {
        return $this->collection($media->files, new FileTransformer());
    }

    private function generateMediaToken($media) {
        $ttl = 30 * 60; // 30 minutes
        $secret_key = env('TOKEN_SECRET');
        $payload = array(
            'id' => $media->id,
            'visitor' => $media->visitor,
            'exp' => time() + $ttl
        );

        $jwt = JWT::encode($payload, $secret_key);

        return [
            'token' => $jwt,
            'ttl' => $ttl
        ];
    }
}