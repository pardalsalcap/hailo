<?php

namespace Pardalsalcap\Hailo\Http\Controllers;

use Illuminate\Http\Request;
use Pardalsalcap\Hailo\Actions\Medias\StoreMedia;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Throwable;
use Exception;
class UploadController
{
    public function upload(Request $request)
    {
        try {
            $repository = new MediaRepository();
            $action = $request->get("action");
            $result = $repository->upload($request);
            if ($result) {
                if (empty($action))
                {
                    $result = StoreMedia::run($result);
                }
                return response()->json([
                    'success' => true,
                    'media' => $result,
                    'message' => 'File uploaded successfully'
                ]);
            }
            throw new Exception('Error uploading file. Please try again.');
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
