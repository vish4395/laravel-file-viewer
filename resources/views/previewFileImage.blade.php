<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/viewerjs/viewer.min.css') }}">
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-hidden bg-slate-800 relative">
        <div id="loading-container" class="absolute inset-0 flex items-center justify-center gap-3 text-white/60">
            <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm">Loading image…</span>
        </div>
        <div id="result-container" class="w-full h-full">
            <img id="image" src="{{ $fileUrl }}" alt="{{ $fileName }}" style="display:none;min-width:200px;">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/viewerjs/viewer.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var image            = document.getElementById('image');
    var loadingContainer = document.getElementById('loading-container');

    function initViewer() {
        new Viewer(image, { inline: true, backdrop: false, navbar: false });
        loadingContainer.style.display = 'none';
    }

    if (image.complete && image.naturalWidth) {
        initViewer();
    } else {
        image.addEventListener('load', initViewer);
        image.addEventListener('error', function () {
            loadingContainer.innerHTML = '<span class="text-sm text-red-400">Failed to load image.</span>';
        });
    }
});
</script>
@endpush
