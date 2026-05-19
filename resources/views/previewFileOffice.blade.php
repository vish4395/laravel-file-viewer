<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 flex flex-col items-center justify-center gap-5 bg-slate-50">
        <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center">
            <i class="{{ $iconClass }} text-4xl text-slate-400"></i>
        </div>
        <p class="text-sm text-slate-500">Preview not available for this file type.</p>
        <a href="{{ $fileUrl }}"
           download="{{ $fileName }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-download"></i>
            Download File
        </a>
    </div>
</div>
@endsection
