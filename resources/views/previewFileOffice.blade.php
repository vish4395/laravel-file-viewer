<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    .fallback-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 85vh;
        flex-direction: column;
        gap: 1rem;
        background: #f8f9fa;
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
        <div class="fallback-container">
            <i class="{{ $iconClass }} fa-4x text-secondary"></i>
            <p class="text-muted mb-1">Preview not available for this file type.</p>
            <a href="{{ $fileUrl }}" download="{{ $fileName }}" class="btn btn-primary">
                <i class="fa-solid fa-download me-1"></i> Download File
            </a>
        </div>
    </div>
</div>

@endsection
