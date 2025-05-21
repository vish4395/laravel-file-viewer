<?php
$page_title=$fileName;
    ?>
    @extends('laravel-file-viewer::layouts.blank_app_no_logo')

    @section('content')
    <script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.2/plyr.css" />
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
        height: 90vh
    }
</style>
<div class="row">
<div class="col-md-12">
    <div class="card file-detail-card m-0">
        <div class="card-body p-1">
                @include('laravel-file-viewer::previewFileDetails')
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="resolte-contaniner" class="preview_container">
         <audio
            controls
            class="w-100"
            style="margin-top: 10%"
            src="{!! $fileUrl !!}">
                Your browser does not support the
                <code>audio</code> element.
        </audio>
    </div>
</div>
</div>
<script>
//     $("#resolte-contaniner").officeToHtml({
//    url: '{!! $fileUrl !!}'
// });
const player = new Plyr('#player');

</script>
@endsection