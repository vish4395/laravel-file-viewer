<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div id="pdf-container" class="flex-1 overflow-y-auto bg-[#525659] p-4">
        <div id="pdf-loading" class="flex items-center justify-center gap-3 text-white/60 py-16">
            <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm">Loading PDF…</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/pdfjs/pdf.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        '{{ asset('vendor/laravel-file-viewer/pdfjs/pdf.worker.min.js') }}';

    pdfjsLib.getDocument({ url: @json($fileUrl) }).promise
        .then(function (pdf) {
            document.getElementById('pdf-loading').style.display = 'none';
            var container = document.getElementById('pdf-container');

            var renderPage = function (pageNum) {
                pdf.getPage(pageNum).then(function (page) {
                    var scale    = Math.min(1.5, (container.clientWidth - 32) / page.getViewport({ scale: 1 }).width);
                    var viewport = page.getViewport({ scale: scale });
                    var canvas   = document.createElement('canvas');
                    canvas.width              = viewport.width;
                    canvas.height             = viewport.height;
                    canvas.style.display      = 'block';
                    canvas.style.margin       = '0 auto 12px auto';
                    canvas.style.boxShadow    = '0 2px 12px rgba(0,0,0,0.5)';
                    canvas.style.borderRadius = '2px';
                    container.appendChild(canvas);
                    page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport });
                });
            };

            for (var p = 1; p <= pdf.numPages; p++) {
                renderPage(p);
            }
        })
        .catch(function (err) {
            var el = document.getElementById('pdf-loading');
            el.textContent = 'Failed to load PDF: ' + err.message;
            el.classList.remove('text-white/60');
            el.classList.add('text-red-400');
        });
});
</script>
@endpush
