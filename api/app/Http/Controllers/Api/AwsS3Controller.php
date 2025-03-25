<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function handleUploadS3(Request $request) {
        try {
            $messages = [
                'carouselFiles.required' => 'The carousel files field is required.',
                'carouselFiles.file' => 'The carousel files must be a file.',
            ];

            $validator = Validator::make($request->all(), [
                'carouselFiles' => 'required|file|mimes:png|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $file = $request->file('carouselFiles');
            $numberOfParts = (int) $request->query('numberOfParts', 2);
            $dirName = $request->query('dirName', 'default_folder');

            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $eachImageWidth = $fullFileWidth / $numberOfParts;

            for ($i = 0; $i < $numberOfParts; $i++) {
                $cloned = imagecreatetruecolor($eachImageWidth, $fullFileHeight);
                imagecopy($cloned, $image, 0, 0, $eachImageWidth * $i, 0, $eachImageWidth, $fullFileHeight);

                ob_start();
                imagepng($cloned);
                $imageRealContent = ob_get_clean();
                $filePath = "{$dirName}/split_{$i}.png";
                
                try {
                    $this->s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $imageRealContent,
                        'ContentType' => 'image/png',
                    ]);
                } catch (S3Exception $e) {
                    return response()->json(['message' => $e->getMessage()], 400);
                } 

                imagedestroy($cloned);
            }
            imagedestroy($image);

            return response()->json(['message' => 'Images splitted and uploaded successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error splitting the file. Please try again.'], 400);
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

            return response()->json($splittedImages, 200);
        } catch (S3Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
