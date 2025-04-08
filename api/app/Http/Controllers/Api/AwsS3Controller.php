<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SplitFileRequest;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AwsS3Controller extends Controller
{
    private $s3;
    
    public function __construct() {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);
    }

    public function handleUploadS3(SplitFileRequest $request) {
        $objectParts = [];

        if(!$request->validated()) {
            return response()->json([
                'message' => 'Invalid data. Please check your input.',
                'error' => $request->errors()
            ], 422);
        }

        try {
            $files = $request->file('carouselFiles');
            $dirName = $request->query('dirName');
    
            foreach ($files as $index => $file) {
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                $fullFileWidth = imagesx($image);
                $fullFileHeight = imagesy($image);
    
                $numberOfParts = (int) round($fullFileWidth / 1080);
                
                if ($numberOfParts < 1 || $numberOfParts > 10) {
                    imagedestroy($image);
                    return response()->json(['error' => "Invalid image dimensions for splitting..."], 400);
                }
    
                $eachImageWidth = $fullFileWidth / $numberOfParts;
    
                for ($i = 0; $i < $numberOfParts; $i++) {
                    $cloned = imagecreatetruecolor($eachImageWidth, $fullFileHeight);
                    imagecopy($cloned, $image, 0, 0, $eachImageWidth * $i, 0, $eachImageWidth, $fullFileHeight);
    
                    ob_start();
                    imagepng($cloned);
                    $imageRealContent = ob_get_clean();
                    $filePath = "{$dirName}/file_{$index}_split_{$i}.png";
    
                    try {
                        $part = $this->s3->putObject([
                            'Bucket' => env("AWS_BUCKET"),
                            'Key' => $filePath,
                            'Body' => $imageRealContent,
                            'ContentType' => 'image/png',
                        ]);

                        $objectParts[] = $part['ObjectURL'];
                    } catch (S3Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 400);
                    }
    
                    imagedestroy($cloned);
                }
                imagedestroy($image);
            }
    
            return response()->json(['data' => $objectParts, 'message' => 'Images splitted and uploaded successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error on splitting the file. Please try again.'], 400);
        }
    }    

    public function showImages(Request $request) {
        $dirName = $request->query('dirName');

        try {
            $splittedImages = [];

            $images = $this->s3->listObjectsV2([
                'Bucket' => env('AWS_BUCKET'),
                'Prefix' => $dirName
            ]);

            foreach ($images['Contents'] as $img) {
                $splittedImages[] = env("AWS_URL") . $img['Key'];
            }

            return response()->json(['data' => $splittedImages, 'message' => 'Object parts found!'], 200);
        } catch (S3Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
