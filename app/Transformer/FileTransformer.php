<?php

namespace App\Transformer;

use App\File;
use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{
    public function transform(File $file)
    {
        return [
            'id' => $file->id,
            'identifier' => $file->identifier,
        ];
    }
}