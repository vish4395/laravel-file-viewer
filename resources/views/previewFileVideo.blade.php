<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

<link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />

<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    .video-wrap {
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
    }
    .video-js {
        width: 100%;
        max-height: 85vh;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card file-detail-card m-0">
            <div class="card-body p-1">
                <div class="row">
                    <div class="col-sm-12">
                        @include('laravel-file-viewer::previewFileDetails')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="video-wrap">
            <video id="my-video"
                   class="video-js vjs-big-play-centered"
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

<script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
@endsection
