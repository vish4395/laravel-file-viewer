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
        height: 90vh
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
    <div id="resolte-contaniner" class="preview_container">
        <iframe id="google" 
        src="https://docs.google.com/a/{{$_SERVER['SERVER_NAME']}}/viewer?url={!! $fileUrl !!}&embedded=true"
         width="100%" height="600" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
        
    </div>
</div>
</div>
<script>
//     $("#resolte-contaniner").officeToHtml({
//    url: '{!! $fileUrl !!}'
// });
function update_viewer() {
      var link = $('#input').val()
      if (link.length <= 1) {
        link = '{!! $fileUrl !!}';
      }
      $('#google').attr('src', 'https://docs.google.com/a/{{$_SERVER['SERVER_NAME']}}/viewer?url=' + link + '&embedded=true');
    }
</script>
@endsection