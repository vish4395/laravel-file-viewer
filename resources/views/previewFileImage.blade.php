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
    /* position: fixed; */
    }
    .preview_container{
        /* border: solid 1px lightgray; */
        overflow: scroll;
        background: white;
        padding: 1em;
        height: 85vh;
        width: 100%;
        min-width: 200px;

    }

    .viewer-container{
        width: 100% !important;
        height: 100% !important;
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
    <div class="text-center" id="loading-container">
        <img src="{{ asset('vendor/laravel-file-viewer/loading.gif') }}" alt="Loading..." class="img-fluid" style="max-width: 100px;">
    </div>
    <div id="result-container" class="preview_container">
        <img id="image" src="{{ $fileUrl }}" alt="Picture" height="100%" style="display: none;min-width: 200px;">
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js" integrity="sha512-wLME0TsJo3lj8nkOEDPkVVMpFblLaQfVZv5+4iuHTPx0lYN3LJQ3DqYjuaT/KkfPO+ZCRiT4AuTGMmJUj05A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css" integrity="sha512-SHDPEe5zOdaB4a9c4XN1ZDV0MBbfXJdMOJuMYv7Ij+A9YPBKAQ8jH2LXSXuEBJSQinmvMNANkdQBJwkagKCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const image = document.getElementById('image');
    const loadingContainer = document.getElementById('loading-container');
    setTimeout(() => {
        new Viewer(image, { inline: true, backdrop: false, navbar: false });
        image.style.display = 'block';
        loadingContainer.style.display = 'none';
    }, 500);
});
</script>
@endsection
