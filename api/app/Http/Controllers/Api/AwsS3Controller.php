<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SplitFileRequest;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AwsS3Controller extends Controller
{
    use ApiResponse;

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

        if (!$request->validated()) {
            return $this->errorResponse('Invalid data. Please check your input.', 422, $request->errors());
        }

        $files = $request->file('carouselFiles');
        $dirName = $request->query('dirName');

        foreach ($files as $index => $file) {
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $numberOfParts = (int) round($fullFileWidth / 1080);

            if ($numberOfParts < 1 || $numberOfParts > 10) {
                imagedestroy($image);
                return $this->errorResponse("Invalid image dimensions for splitting...", 400);
            }

            $eachImageWidth = $fullFileWidth / $numberOfParts;

            for ($i = 0; $i < $numberOfParts; $i++) {
                $cloned = imagecreatetruecolor($eachImageWidth, $fullFileHeight);
                imagecopy($cloned, $image, 0, 0, $eachImageWidth * $i, 0, $eachImageWidth, $fullFileHeight);

                ob_start();
                imagepng($cloned);
                $imageRealContent = ob_get_clean();
                $filePath = "{$dirName}/file_{$index}_split_{$i}.png";

                $part = $this->s3->putObject([
                    'Bucket' => env("AWS_BUCKET"),
                    'Key' => $filePath,
                    'Body' => $imageRealContent,
                    'ContentType' => 'image/png',
                ]);

                $objectParts[] = $part['ObjectURL'];

                imagedestroy($cloned);
            }
            imagedestroy($image);
        }

        return $this->successResponse($objectParts, 'Images splitted and uploaded successfully!');
    }    

    public function showImages(Request $request) {
        $dirName = $request->query('dirName');
        
        $splittedImages = [];
        
        $images = $this->s3->listObjectsV2([
            'Bucket' => env('AWS_BUCKET'),
            'Prefix' => $dirName
        ]);

        foreach ($images['Contents'] as $img) {
            $splittedImages[] = env("AWS_URL") . $img['Key'];
        }

        return $this->successResponse($splittedImages, 'Object parts found!');
    }
}