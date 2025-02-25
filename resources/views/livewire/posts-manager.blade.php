<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumi</title>
    @livewireScripts
    @vite("resources/css/app.css")
</head>
<body>
    <div class="bg-white rounded-2xl shadow-lg p-6 w-96">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">Upload files</h2>
            <button class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <p class="text-sm text-gray-500">Select and upload the files of your choice</p>
        
        @if ($canvaFilePreview)
            <div class="mt-4 border-dashed border-2 border-gray-300 rounded-lg p-6 text-center">
                <img src="{{ $canvaFilePreview->temporatyUrl() }}">
            </div>
        @else
            <div class="mt-4 border-dashed border-2 border-gray-300 rounded-lg p-6 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <span class="text-3xl">&#8682;</span>
                    <p class="mt-2">Choose a file or drag & drop it here</p>
                    <p class="text-xs text-gray-400">JPEG, PNG, PDG, and MP4 formats, up to 50MB</p>
                    <button class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Browse File</button>
                </div>
            </div>
        @endif
        
        <div class="mt-4 flex items-center justify-between bg-gray-100 p-3 rounded-lg">
            <div class="flex items-center space-x-2">
                <div class="bg-red-500 text-white text-xs px-2 py-1 rounded-md">PDF</div>
                <span class="text-sm text-gray-700">my-cv.pdf</span>
            </div>
            <span class="text-sm text-gray-500">Uploading...</span>
        </div>
    </div>   
    @livewireStyles
</body>
</html>