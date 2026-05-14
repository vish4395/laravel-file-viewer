<?php
    $page_title=$fileName;
    ?>
    @extends('laravel-file-viewer::layouts.blank_app_no_logo')

    @section('content')

<style>
    .file-detail-card{
        width: 100%;
        bottom: 0px;
        left: 0px;
        z-index: 999;
        background: #ffffffed;
    }
    .pdf-container{
        overflow-y: auto;
        height: 90vh;
        background: #525659;
        padding: 1em;
        width: 100%;
    }
    #pdf-loading{
        color: #ffffff;
        text-align: center;
        padding: 2em;
        font-size: 1.1em;
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
    <div id="pdf-container" class="pdf-container">
        <div id="pdf-loading">Loading PDF...</div>
    </div>
</div>
</div>

<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/pdf/pdf.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    PDFJS.workerSrc = "{{ asset('vendor/laravel-file-viewer/officetohtml/pdf/pdf.worker.js') }}";
    PDFJS.getDocument(@json($fileUrl)).then(function(pdf) {
        document.getElementById('pdf-loading').style.display = 'none';
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            pdf.getPage(pageNum).then(function(page) {
                const scale = 1.5;
                const viewport = page.getViewport(scale);
                const canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                canvas.style.display = 'block';
                canvas.style.margin = '0 auto 10px auto';
                canvas.style.boxShadow = '0 2px 8px rgba(0,0,0,0.4)';
                document.getElementById('pdf-container').appendChild(canvas);
                page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport });
            });
        }
    }).catch(function(err) {
        document.getElementById('pdf-loading').textContent = 'Failed to load PDF.';
    });
});
</script>
@endsection
