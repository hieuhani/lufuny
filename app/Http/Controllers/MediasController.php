<?php

namespace App\Http\Controllers;

use App\Category;
use App\Media;
use App\Transformer\MediaTransformer;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class MediasController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:255',
            'nickname' => 'max:100',
            'photo' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200',
            'category_id' => 'integer',
        ]);
        $mediaType = $request['type'] ? $request['type'] : 1;
        $input = [
            'description' => htmlspecialchars(stripslashes($request['description'])),
            'nickname' => htmlspecialchars(stripslashes($request['nickname'])),
            'active' => false,
            'type' => $mediaType
        ];

        switch ($mediaType) {
            case 1: // Normal photo
                if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    $file = $request->file('photo');
                    $isGIF = $file->getClientMimeType() === 'image/gif';
                    $fileExt = $isGIF ? '.gif' : '.jpg';
                    $fileName = md5(time());

                    $uploadFolder = env('IMAGE_FOLDER', 'uploads/images/') . date('FY') . '/';

                    while (file_exists($uploadFolder . $fileName . $fileExt)) {
                        $fileName = $fileName . '_' . uniqid();
                    }
                    $fileName = $fileName . $fileExt;
                    $successUpload = $file->move($uploadFolder, $fileName);
                    if ($successUpload) {
                        if ($isGIF) {
                            $gifImageFile = str_replace('.gif', '-animation.gif', $fileName);
                            copy($uploadFolder . $fileName, $uploadFolder . $gifImageFile);
                        }

                        $image = Image::make($uploadFolder . $fileName);
                        $image->resize(700, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $image->save($uploadFolder . $fileName);

                        $input['media_url'] = $fileName;
                    }
                }
                break;
            case 2: // Link youtube
                break;
            default:
                return response()->json([
                    'status' => 400,
                    'error' => 'Bad request',
                    'reason' => 'Media type error'
                ], 400);
        }

        if ($request['category_id']) {
            $category = Category::find($request['category_id']);
            if ($category) {
                $input['category_id'] = $category->id;
            }
        }

        $media = Media::create($input);

        return $this->item($media, new MediaTransformer());
    }
}
