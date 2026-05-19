<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

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

<link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@videojs/themes@1/dist/forest/index.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
@endsection
