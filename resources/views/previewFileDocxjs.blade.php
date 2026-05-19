@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    #docx-frame {
        width: 100%;
        height: 85vh;
        border: none;
        display: block;
        background: #525659;
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
        {{-- Render inside an iframe so Bootstrap's CSS is completely isolated --}}
        <iframe id="docx-frame"
            src="{{ URL::temporarySignedRoute('laravel-file-viewer.docx-frame', now()->addHour(), ['url' => $fileUrl]) }}"
            title="{{ $fileName }}">
        </iframe>
    </div>
</div>

@endsection
