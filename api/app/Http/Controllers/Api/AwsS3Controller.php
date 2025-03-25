<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;

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
        $messages = [
            'carouselFiles.required' => 'The carousel files field is required.',
            'carouselFiles.file' => 'The carousel files must be a file.',
            'numberOfParts.required' => 'The number of parts field is required.',
            'numberOfParts.integer' => 'The number of parts must be an integer.',
        ];

        $validator = Validator::make($request->all(), [
            'carouselFiles' => 'required|file|mimes:png,jpg,jpeg|max:1024',
            'numberOfParts' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $carouselFiles = $request->files('carouselFiles');

        $dirName = $request->query('dirName');

        $this->splitFile($carouselFiles, $dirName, $request->numberOfParts);
    }

    public function showImages(Request $request) {
        $dirName = $request->query('dirName');

        $images = $this->s3->listObjects([
            'Bucket' => env('AWS_BUCKET'),
            'Prefix' => $dirName
        ]);

        return response()->json($images, 200);
    }

    private function splitFile($carouselFiles, $dirName, $numberOfParts) {
        try {
            foreach ($carouselFiles as $file) {
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                $fullFileWidth = imagesx($image);
                $fullFileHeight = imagesy($image);
    
                $imagesQuantity = (int) $numberOfParts;
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
                    $filePath = "{$dirName}/split_{$i}.png";
                    
                    $this->s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $imageRealContent,
                    ]); 
                    
                    imagedestroy($cloned);
                }
                imagedestroy($image);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error splitting the file. Please try again.'], 400);
        }

        return response()->json(['message' => 'Images splitted and uploaded successfully!'], 200);
    }
}
