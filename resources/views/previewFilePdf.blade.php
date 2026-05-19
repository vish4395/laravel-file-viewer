<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    .pdf-container {
        overflow-y: auto;
        height: 85vh;
        background: #525659;
        padding: 1em;
        width: 100%;
    }
    #pdf-loading {
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
            <div id="pdf-loading">Loading PDF…</div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" integrity="sha384-/1qUCSGwTur9vjf/z9lmu/eCUYbpOTgSjmpbMQZ1/CtX2v/WcAIKqRv+U1DUCG6e" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    pdfjsLib.getDocument({ url: @json($fileUrl) }).promise
        .then(function (pdf) {
            document.getElementById('pdf-loading').style.display = 'none';
            var container = document.getElementById('pdf-container');

            var renderPage = function (pageNum) {
                pdf.getPage(pageNum).then(function (page) {
                    var scale = Math.min(1.5, (container.clientWidth - 32) / page.getViewport({ scale: 1 }).width);
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.createElement('canvas');
                    canvas.width  = viewport.width;
                    canvas.height = viewport.height;
                    canvas.style.display   = 'block';
                    canvas.style.margin    = '0 auto 10px auto';
                    canvas.style.boxShadow = '0 2px 8px rgba(0,0,0,0.4)';
                    container.appendChild(canvas);
                    page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport });
                });
            };

            for (var p = 1; p <= pdf.numPages; p++) {
                renderPage(p);
            }
        })
        .catch(function (err) {
            document.getElementById('pdf-loading').textContent = 'Failed to load PDF: ' + err.message;
            document.getElementById('pdf-loading').style.color = '#f88';
        });
});
</script>

@endsection
