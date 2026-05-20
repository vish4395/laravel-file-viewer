<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/prism/prism.min.css') }}">
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-auto bg-white">
        <pre id="text-preview" class="language-plaintext m-0 min-h-full text-sm leading-relaxed p-4"></pre>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/prism/prism.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/prism/prism-autoloader.min.js') }}"
        data-autoloader-path="{{ asset('vendor/laravel-file-viewer/prism/components/') }}"></script>
<script>
fetch(@json($fileUrl))
    .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
    .then(function (text) {
        var pre = document.getElementById('text-preview');
        var ext = @json(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
        pre.className = 'language-' + (ext || 'plaintext') + ' m-0 min-h-full text-sm leading-relaxed p-4';
        pre.textContent = text;
        Prism.highlightElement(pre);
    })
    .catch(function (err) {
        var pre = document.getElementById('text-preview');
        pre.textContent = 'Failed to load file: ' + err.message;
    });
</script>
@endpush
