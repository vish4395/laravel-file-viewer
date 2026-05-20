<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<link href="{{ asset('vendor/laravel-file-viewer/videojs/video-js.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/laravel-file-viewer/videojs/themes/forest/index.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 bg-black flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <video id="my-video"
                   class="video-js vjs-theme-forest vjs-big-play-centered"
                   controls
                   preload="auto"
                   data-setup='{"fluid": true, "responsive": true}'>
                <source src="{{ $fileUrl }}" />
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a
                    web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>.
                </p>
            </video>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/videojs/video.min.js') }}"></script>
@endpush
