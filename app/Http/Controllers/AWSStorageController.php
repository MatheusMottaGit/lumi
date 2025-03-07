<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Validator;

class AWSStorageController extends Controller
{
    private $s3Client;

    public function __construct() {
        $this->s3Client =  new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);  
    }

    public function splitUploadS3File(Request $request) {
        $validator = Validator::make($request->all(), [
            'canvaFiles.*' => 'required|mimes:png,jpg|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error.'], 422);
        }

        $canvaFiles = $request->file("canvaFiles");

        foreach ($canvaFiles as $file) {
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $imagesQuantity = 6;
            $eachImageWidth = $fullFileWidth / $imagesQuantity;

            for ($i=0; $i < $imagesQuantity; $i++) { 
                $cloned = imagecreatetruecolor($eachImageWidth, $fullFileHeight);
                imagecopy(
                    $cloned,
                    $image,
                    0,
                    0,
                    $eachImageWidth * $i,
                    0,
                    $eachImageWidth,
                    $fullFileHeight
                );

                ob_start();
                imagepng($cloned);
                $imageRealContent = ob_get_clean();
                $filePath = "posts/split_{$i}.png";
                
                try {
                    $this->s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $imageRealContent,
                    ]);        
                } catch (Aws\S3\Exception\S3Exception $e) {
                    dd($e);
                }

                imagedestroy($cloned);
            }
            imagedestroy($image);
        }

        return response()->json(['message' => "Files uploaded successfuly!"], 200);
    }

    public function showUploadedFiles() {
        $splittedImagesUrl = [];

        try {
            $images = $this->s3Client->listObjectsV2([
                'Bucket' => env("AWS_BUCKET")
            ]);

            foreach (array_slice($images['Contents'], 1) as $img) {
                $splittedImagesUrl[] = env("AWS_URL") . $img['Key'];
            }
        } catch (Aws\S3\Exception\S3Exception $e) {
            dd($e);
        }

        return response()->json(['message' => $splittedImagesUrl], 200);
    }
}
