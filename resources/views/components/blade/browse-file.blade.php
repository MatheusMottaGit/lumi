<header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
  <img src="{{ url('svg/cloud-upload.svg') }}" class="h-8 w-8">

  <div class="space-y-0.5">
    <h2 class="text-xl text-gray-100 font-semibold">Browse file(s)</h2>
    <p class="text-md text-gray-500">Select and upload the file(s) of your choice</p>
  </div>
</header>

<div class="w-full p-4 space-y-3">
  <div
    class="border-dashed border bg-gray-900/20 hover:bg-gray-900/40 cursor-pointer border-gray-700 rounded-lg h-72 p-6 text-center flex items-center justify-center">
    <label for="canvaFile">
      <div class="flex flex-col items-center justify-center text-gray-500">
        <img src="{{ url('svg/upload.svg') }}" class="w-6 h-6">
        <p class="mt-2 text-lg">Choose a file or drag & drop it here</p>
        <p class="text-lg text-gray-400">JPEG, PNG, PDG, and PDF formats, up to 50MB</p>
      </div>
      <input type="file" id="canvaFile" class="hidden" wire:model="canvaFiles" multiple>
    </label>
  </div>

  @if (!empty($canvaFiles))
  @foreach ($canvaFiles as $file)
  <div class="flex flex-col gap-3">
    <div class="w-full flex items-center bg-gray-900 gap-2 p-3 rounded-lg">
      <div class="bg-sky-700 text-gray-200 px-2 py-1 rounded-md">PNG</div>
      <span class="text-gray-500">{{ $file->getClientOriginalName() }}</span>
    </div>
  </div>
  @endforeach
  @else
  <div class="w-full flex items-center gap-2 bg-gray-900 p-3 rounded-lg">
    <img src="{{ url('svg/file-check-2.svg') }}">
    <span class="text-gray-500 text-center">Your file(s) will appear right here.</span>
  </div>
  @endif
</div>