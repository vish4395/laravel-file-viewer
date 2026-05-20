<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/css/pluginsCss.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/plugins.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/css/luckysheet.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/assets/iconfont/iconfont.css">

<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 relative bg-white">
        <div id="excel-loading" class="absolute inset-0 flex items-center justify-center gap-3 text-slate-400">
            <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm">Loading spreadsheet…</span>
        </div>
        <div id="ls-wrap" class="absolute inset-0" style="display:none;">
            <div id="luckysheet" style="position:absolute;width:100%;height:100%;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/plugins/js/plugin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.13/dist/luckysheet.umd.js"></script>
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
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            });

            LuckyExcel.transformExcelToLucky(file, function (exportJson) {
                if (!exportJson || !exportJson.sheets || !exportJson.sheets.length) {
                    document.getElementById('excel-loading').innerHTML =
                        '<p class="text-amber-600 text-sm">No sheets found.</p>';
                    return;
                }

                document.getElementById('excel-loading').style.display = 'none';
                document.getElementById('ls-wrap').style.display = '';

                luckysheet.create({
                    container:             'luckysheet',
                    data:                  exportJson.sheets,
                    title:                 (exportJson.info && exportJson.info.name) || 'Spreadsheet',
                    lang:                  'en',
                    showtoolbar:           false,
                    showinfobar:           false,
                    showstatisticBar:      false,
                    sheetBottomConfig:     false,
                    allowEdit:             false,
                    enableAddRow:          false,
                    enableAddCol:          false,
                    showsheetbar:          true,
                    showConfigWindowResize: false,
                });
            });
        })
        .catch(function (err) {
            document.getElementById('excel-loading').innerHTML =
                '<p class="text-red-500 text-sm">Failed to load spreadsheet: ' + err.message + '</p>';
        });
});
</script>
@endsection
