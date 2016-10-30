<?php

namespace App\Http\Controllers;


use App\Media;
use App\Transformer\MediaTransformer;

class ModeratorController extends Controller
{

    public function mod()
    {
        return 'moderator';
    }

    public function inActiveMedias()
    {
        $medias = Media::where('active', false)->get();
        return $this->collection($medias, new MediaTransformer());
    }

    public function toggleActiveMedia($id)
    {
        $media = Media::find($id);
        if (is_null($media)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found',
                'reason' => 'Media not found'
            ], 404);
        }
        $media->active = !$media->active;
        $media->save();
        return $this->item($media, new MediaTransformer());
    }
}
