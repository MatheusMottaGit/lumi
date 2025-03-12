@extends('components.layouts.auth')

@section('login')
    <div class="text-center text-gray-200 space-y-6">
        <div class="flex justify-center">
            <img src="{{ url('svg/fingerprint.svg') }}" class="h-12 w-12">
        </div>

        <h1 class="text-2xl font-semibold">Join Lumi!</h1>

        <p class="text-gray-400">You must have an <span class="text-gray-200 font-medium">Instagram Business</span> <br> account to continue.</p>

        <button class="mx-auto cursor-pointer flex items-center justify-center gap-2 bg-sky-800 hover:bg-sky-700 text-gray-200 font-medium py-2 px-6 rounded-lg">
            <img src="{{ url('svg/facebook.svg') }}" class="h-5 w-5">
            Sign in with Facebook
        </button>

        <p class="text-sm text-gray-500">
            By clicking continue, you agree to our <br> <a href="#" class="text-sky-400 hover:underline">Terms of Service</a> and <a href="#" class="text-sky-400 hover:underline">Privacy Policy</a>.</p>
    </div>
@endsection