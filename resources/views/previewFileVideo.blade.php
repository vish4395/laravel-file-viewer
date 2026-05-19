<?php
    $page_title=$fileName;
    ?>
    @extends('laravel-file-viewer::layouts.blank_app_no_logo')

    @section('content')
    <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
<link href="https://unpkg.com/@videojs/themes@1/dist/forest/index.css" rel="stylesheet">

<style>
    .file-detail-card{
        width: 100%;
    bottom: 0px;
    left: 0px;
    z-index: 999;
    background: #ffffffed;
    /* position: fixed; */
    }
    .preview_container{
        /* border: solid 1px lightgray; */
        overflow: scroll;
        background: white;
        padding: 1em;
        height: 90vh
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
    <div id="result-container" class="preview_container">
        <div class="embed-responsive embed-responsive-16by9 text-center p-2 mx-auto border border-primary" style="max-width: 650px;max-height: 100%;">
        <video id="my-video" class="video-js vjs-theme-forest embed-responsive-item" controls preload="auto"
        data-setup="{}">
            <source src="{{ $fileUrl }}" />
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a
                web browser that
                <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
            </p>
        </video>
        </div>
    </div>
</div>
</div>

<script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
@endsection
