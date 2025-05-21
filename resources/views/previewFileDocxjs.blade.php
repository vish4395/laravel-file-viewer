@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<style>
    .docxjs-container {
        background: #fff;
        padding: 1em;
        min-height: 80vh;
        overflow: auto;
    }
    .docx {
        color: #222;
        font-family: 'Segoe UI', 'Arial', sans-serif;
        font-size: 1em;
        line-height: 1.5;
    }
</style>

@include('laravel-file-viewer::docstyledef')
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
        <div id="docxjs-viewer" class="docxjs-container"></div>
    </div>
</div>

<script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/docx-preview-lib@0.1.14-fix-3/dist/docx-preview.min.js"></script> --}}
<script src="{{ asset('vendor/laravel-file-viewer/docx-preview/docx-preview.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const url = @json($fileUrl);
        const container = document.getElementById('docxjs-viewer');
        const docxOptions = Object.assign({}, window.docx.defaultOptions, {
            debug: false,
            experimental: true,
            hideWrapperOnPrint: true
        });

        fetch(url)
            .then(response => response.blob())
            .then(blob => window.docx.renderAsync(blob, container, null, docxOptions))
            .catch(e => {
                container.innerHTML = '<div class="alert alert-danger">Failed to load DOCX file.</div>';
            });
    });
</script>
@endsection