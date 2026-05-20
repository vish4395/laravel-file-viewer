<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/css/pptxjs.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/css/nv.d3.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/divs2slides.css') }}">
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-auto bg-slate-200 p-4">
        <div id="pptx-container" class="min-h-full"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/d3.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/nv.d3.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/filereader.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/divs2slides.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/pptxjs.js') }}"></script>
<script>
$(document).ready(function () {
    $('#pptx-container').pptxToHtml({
        pptxFileUrl:      @json($fileUrl),
        slidesScale:      @json(config('laravel-file-viewer.pptx_slides_scale', '50%')),
        slideMode:        true,
        slideType:        'divs2slidesjs',
        slideModeConfig: {
            first:             1,
            nav:               true,
            navTxtColor:       'black',
            showSlideNum:      true,
            showTotalSlideNum: true,
            loop:              true,
            transition:        'default',
            transitionTime:    1,
        },
    });
});
</script>
@endpush
