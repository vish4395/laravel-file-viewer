<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-hidden bg-[#525659]">
        <iframe id="docx-frame"
                class="w-full h-full border-0 block"
                src="{{ URL::temporarySignedRoute('laravel-file-viewer.docx-frame', now()->addHour(), ['url' => $fileUrl]) }}"
                title="{{ $fileName }}">
        </iframe>
    </div>
</div>
@endsection
