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
        $request->validate([
            'carouselFiles' => 'required|file|mimes:png,jpg,jpeg|max:1024',
            'numberOfImages' => 'required|integer',
        ]);

        $carouselFiles = $request->files('carouselFiles');

        $dirName = $request->query('dirName');

        $this->splitFile($carouselFiles, $dirName, $request->numberOfImages);
    }

    public function showImages(Request $request) {
        $dirName = $request->query('dirName');

        $images = $this->s3->listObjects([
            'Bucket' => env('AWS_BUCKET'),
            'Prefix' => $dirName
        ]);

        return response()->json($images, 200);
    }

    private function splitFile($carouselFiles, $dirName, $numberOfImages) {
        foreach ($carouselFiles as $file) {
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $imagesQuantity = (int) $numberOfImages;
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
                
                try {
                    $this->s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $imageRealContent,
                    ]);        
                } catch (S3Exception $e) {
                    dd($e);
                }
                imagedestroy($cloned);
            }
            imagedestroy($image);
        }

        return response()->json(['message' => 'Images splitted and uploaded successfully!'], 200);
    }
}
