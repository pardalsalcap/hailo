<?php

namespace Pardalsalcap\Hailo\Http\Controllers;

use Illuminate\Http\Request;
use Pardalsalcap\Hailo\Actions\Medias\StoreMedia;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Throwable;
class UploadController
{
    public function upload(Request $request)
    {
        try {
            $repository = new MediaRepository();
            $result = $repository->upload($request);
            if ($result) {
                $result = StoreMedia::run($result);
                return response()->json([
                    'success' => true,
                    'media' => $result,
                    'message' => 'File uploaded successfully'
                ]);
            }
        }
        catch (Throwable $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file. Please try again. '.$e->getMessage()
            ]);
        }

    }
}
