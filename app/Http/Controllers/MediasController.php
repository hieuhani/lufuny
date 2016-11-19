<?php

namespace App\Http\Controllers;

use App\Category;
use App\File;
use App\Media;
use App\Transformer\FileTransformer;
use App\Transformer\MediaTransformer;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class MediasController extends Controller
{
    /**
     * Get all active medias
     * @return array
     */
    public function index()
    {
        $medias = Media::where('active', true)->paginate(10);
        return $this->collection($medias, new MediaTransformer());
    }

    /**
     * Create new media
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:255',
            'nickname' => 'max:100',
            'category_id' => 'integer',
        ]);
        $input = [
            'description' => htmlspecialchars(stripslashes($request['description'])),
            'nickname' => htmlspecialchars(stripslashes($request['nickname'])),
            'active' => false,
        ];

        if ($request['category_id']) {
            $category = Category::find($request['category_id']);
            if ($category) {
                $input['category_id'] = $category->id;
            }
        }

        $media = Media::create($input);

        return $this->item($media, new MediaTransformer());
    }

    /**
     * Add file to media
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function addFile($id, Request $request) {
        $this->validate($request, [
            'photo' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200',
            'type' => 'required|integer',
            'video_url' => 'max:255',
        ]);

        $media = Media::find($id);

        if (is_null($media)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found',
                'reason' => 'Media not found',
            ], 404);
        }

        $mediaType = $request['type'];

        $input = [
            'media_id' => $media->id,
            'type' => $mediaType,
        ];

        switch ($mediaType) {
            case 1: // Normal photo
                if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    $file = $request->file('photo');
                    $isGIF = $file->getClientMimeType() === 'image/gif';
                    $fileExt = $isGIF ? '.gif' : '.jpg';
                    $fileName = md5(time());

                    $uploadFolder = env('IMAGE_FOLDER', 'uploads/images/') . date('Ym') . '/';

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

                        $input['identifier'] = $fileName;
                    }
                } else {
                    return response()->json([
                        'status' => 400,
                        'error' => 'Bad request',
                        'reason' => 'No image to upload'
                    ], 400);
                }
                break;
            case 2: // Link youtube
                if (isset($request['video_url'])) {
                    $videoURL = $request['video_url'];
                    if (strpos($videoURL, 'youtube') > 0 || strpos($videoURL, 'youtu.be') > 0) {
                        $videoID = \YouTubeHelper::extractUTubeVidId($videoURL);
                        if (isset($videoID[1])) {
                            $thumbnailURL = 'http://img.youtube.com/vi/'. $videoID . '/0.jpg';
                            $media->thumbnail = $thumbnailURL;
                            $media->save();

                            $input['identifier'] = $videoID;
                        }
                    } else {
                        return response()->json([
                            'status' => 400,
                            'error' => 'Bad request',
                            'reason' => 'Video URL error'
                        ], 400);
                    }
                }
                break;
            default:
                return response()->json([
                    'status' => 400,
                    'error' => 'Bad request',
                    'reason' => 'Media type error'
                ], 400);
        }

        $file = File::create($input);

        return $this->item($file, new FileTransformer());
    }

    /**
     * Vote or remove vote a media
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function toggleVoteMedia($id, Request $request)
    {
        $user = $request['user'];
        if (!($user instanceof User)) {
            return response()->json([
                'status' => 403,
                'error' => 'Forbidden',
                'reason' => 'Forbidden user',
            ], 403);
        }

        $media = Media::find($id);
        if (is_null($media)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found',
                'reason' => 'Media not found',
            ], 404);
        }

        if ($user->votedMedias->where('id', $media->id)->first()) {
            $user->votedMedias()->detach($user->id);
        } else {
            $user->votedMedias()->attach($user->id);
        }

        return $this->item($media, new MediaTransformer());
    }
}
