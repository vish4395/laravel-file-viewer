<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

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
    .video-wrap video {
        width: 100%;
        max-height: 85vh;
        display: block;
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
            <video controls preload="metadata" src="{{ $fileUrl }}">
                Your browser does not support HTML5 video.
            </video>
        </div>
    </div>
</div>

@endsection
