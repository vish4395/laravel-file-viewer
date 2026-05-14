@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/css/pptxjs.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/css/nv.d3.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/divs2slides.css') }}">

<style>
    .pptx-wrapper {
        min-height: 85vh;
        overflow: auto;
        background: #f5f5f5;
        padding: 1em;
    }
    #pptx-container {
        min-height: 80vh;
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
        <div class="pptx-wrapper">
            <div id="pptx-container"></div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/d3.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/nv.d3.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/filereader.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/divs2slides.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/PPTXjs/js/pptxjs.js') }}"></script>

<script>
    $(document).ready(function() {
        $("#pptx-container").pptxToHtml({
            pptxFileUrl: @json($fileUrl),
            slidesScale: "50%",
            slideMode: true,
            slideType: "divs2slidesjs",
            slideModeConfig: {
                first: 1,
                nav: true,
                navTxtColor: "black",
                showSlideNum: true,
                showTotalSlideNum: true,
                loop: true,
                transition: "default",
                transitionTime: 1
            }
        });
    });
</script>
@endsection
