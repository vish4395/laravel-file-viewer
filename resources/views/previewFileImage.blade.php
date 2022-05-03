<?php
    $page_title=$filename;
    ?>
    @extends('laravel-file-viewer::layouts.blank_app_no_logo')

    @section('content')
<!--PDF--> 
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/pdf/pdf.viewer.css') }}"> 
<script src="{{ asset('plugins/officetohtml/pdf/pdf.js') }}"></script> 
<!--Docs-->
<script src="{{ asset('plugins/officetohtml/docx/jszip-utils.js') }}"></script>
<script src="{{ asset('plugins/officetohtml/docx/mammoth.browser.min.js') }}"></script>
<!--PPTX-->
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/PPTXjs/css/pptxjs.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/PPTXjs/css/nv.d3.min.css') }}">

<script type="text/javascript" src="{{ asset('plugins/officetohtml/PPTXjs/js/filereader.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/officetohtml/PPTXjs/js/d3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/officetohtml/PPTXjs/js/nv.d3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/officetohtml/PPTXjs/js/pptxjs.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/officetohtml/PPTXjs/js/divs2slides.js') }}"></script>

<!--All Spreadsheet -->
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/SheetJS/handsontable.full.min.css') }}">
<script type="text/javascript" src="{{ asset('plugins/officetohtml/SheetJS/handsontable.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/officetohtml/SheetJS/xlsx.full.min.js') }}"></script>
<!--Image viewer--> 
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/verySimpleImageViewer/css/jquery.verySimpleImageViewer.css') }}">
<script type="text/javascript" src="{{ asset('plugins/officetohtml/verySimpleImageViewer/js/jquery.verySimpleImageViewer.js') }}"></script>
<!--officeToHtml-->
<script src="{{ asset('plugins/officetohtml/officeToHtml/officeToHtml.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/officetohtml/officeToHtml/officeToHtml.css') }}">
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
    <div id="resolte-contaniner" class="preview_container"></div>
</div>
</div>
<script>
    $(function () {
    $("#resolte-contaniner").officeToHtml({
   url: '{!! $file_url !!}'
});
})
</script>
@endsection