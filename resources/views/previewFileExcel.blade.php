@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

<link rel="stylesheet" href="{{ asset('vendor/laravel-file-viewer/officetohtml/SheetJS/handsontable.full.min.css') }}">

<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }
    #sheet-tabs {
        margin-bottom: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 4px 0;
    }
    .sheet-tab-btn {
        padding: 4px 14px;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        background: #f8f9fa;
        color: #495057;
        cursor: pointer;
        font-size: 0.85em;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }
    .sheet-tab-btn:hover {
        background: #e2e6ea;
        border-color: #adb5bd;
    }
    .sheet-tab-btn.active {
        background: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    #hot-container {
        height: 82vh;
        overflow: hidden;
    }
    #excel-loading {
        color: #555;
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
        <div id="sheet-tabs"></div>
        <div id="hot-container" style="height:82vh;overflow:hidden;"></div>
        <div id="excel-loading" class="text-center p-3">Loading spreadsheet...</div>
    </div>
</div>

<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/SheetJS/xlsx.full.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/SheetJS/handsontable.full.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileUrl = @json($fileUrl);
    var hotInstance = null;

    function renderSheet(ws) {
        var data = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });

        if (hotInstance) {
            hotInstance.destroy();
            hotInstance = null;
        }

        var container = document.getElementById('hot-container');
        hotInstance = new Handsontable(container, {
            data: data,
            readOnly: true,
            licenseKey: 'non-commercial-and-evaluation',
            width: '100%',
            height: '100%',
            colHeaders: true,
            rowHeaders: true,
            manualColumnResize: true,
            filters: true,
            dropdownMenu: true
        });

        document.getElementById('excel-loading').style.display = 'none';
    }

    function buildTabs(wb) {
        var tabsContainer = document.getElementById('sheet-tabs');
        tabsContainer.innerHTML = '';

        wb.SheetNames.forEach(function (name, index) {
            var btn = document.createElement('button');
            btn.className = 'sheet-tab-btn' + (index === 0 ? ' active' : '');
            btn.textContent = name;
            btn.addEventListener('click', function () {
                tabsContainer.querySelectorAll('.sheet-tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                renderSheet(wb.Sheets[name]);
            });
            tabsContainer.appendChild(btn);
        });
    }

    fetch(fileUrl)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.arrayBuffer();
        })
        .then(function (buf) {
            var wb = XLSX.read(buf, { type: 'array' });
            buildTabs(wb);
            if (wb.SheetNames.length > 0) {
                renderSheet(wb.Sheets[wb.SheetNames[0]]);
            } else {
                document.getElementById('excel-loading').textContent = 'No sheets found in this workbook.';
            }
        })
        .catch(function (err) {
            document.getElementById('excel-loading').innerHTML =
                '<div class="alert alert-danger">Failed to load spreadsheet: ' + err.message + '</div>';
        });
});
</script>

@endsection
