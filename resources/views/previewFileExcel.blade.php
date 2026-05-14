@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

{{-- Luckysheet requires these four CSS files in this order --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/css/pluginsCss.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/plugins.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/css/luckysheet.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/assets/iconfont/iconfont.css">

<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    #ls-wrap {
        position: relative;
        width: 100%;
        height: 82vh;
        border: 1px solid #dee2e6;
    }
    #luckysheet {
        position: absolute;
        width: 100%;
        height: 100%;
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

    <div class="col-md-12 mt-2">
        <div id="excel-loading" class="text-center p-4">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2 text-muted">Loading spreadsheet…</div>
        </div>
        <div id="ls-wrap" style="display:none;">
            <div id="luckysheet"></div>
        </div>
    </div>
</div>

{{-- Luckysheet plugin bundle (charts, etc.) must load before the core --}}
<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/js/plugin.js"></script>
{{-- Luckysheet core --}}
<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/luckysheet.umd.js"></script>
{{-- LuckyExcel: converts .xlsx → Luckysheet JSON (preserves charts, formulas, styles) --}}
<script src="https://cdn.jsdelivr.net/npm/luckyexcel@1.0.1/dist/luckyexcel.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileUrl = @json($fileUrl);

    fetch(fileUrl)
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.arrayBuffer();
        })
        .then(function (buf) {
            var file = new File([buf], 'spreadsheet.xlsx', {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });

            LuckyExcel.transformExcelToLucky(file, function (exportJson) {
                if (!exportJson || !exportJson.sheets || !exportJson.sheets.length) {
                    document.getElementById('excel-loading').innerHTML =
                        '<div class="alert alert-warning">No sheets found.</div>';
                    return;
                }

                document.getElementById('excel-loading').style.display = 'none';
                document.getElementById('ls-wrap').style.display = '';

                luckysheet.create({
                    container: 'luckysheet',
                    data: exportJson.sheets,
                    title: exportJson.info ? exportJson.info.name : 'Spreadsheet',
                    lang: 'en',
                    // read-only viewer config
                    showtoolbar: false,
                    showinfobar: false,
                    showstatisticBar: false,
                    sheetBottomConfig: false,
                    allowEdit: false,
                    enableAddRow: false,
                    enableAddCol: false,
                    showsheetbar: true,
                    showConfigWindowResize: false,
                });
            });
        })
        .catch(function (err) {
            document.getElementById('excel-loading').innerHTML =
                '<div class="alert alert-danger">Failed to load spreadsheet: ' + err.message + '</div>';
        });
});
</script>

@endsection
