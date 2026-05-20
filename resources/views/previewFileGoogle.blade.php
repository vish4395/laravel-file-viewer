<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-hidden bg-slate-100">
        <iframe id="google"
                class="w-full h-full border-0 block"
                src="https://docs.google.com/a/{{ request()->getHost() }}/viewer?url={{ urlencode($fileUrl) }}&embedded=true"
                allowfullscreen>
        </iframe>
    </div>
</div>
@endsection
