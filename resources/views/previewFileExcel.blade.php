@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')

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
    .sheet-tab-btn:hover { background: #e2e6ea; border-color: #adb5bd; }
    .sheet-tab-btn.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }
    #sheet-wrapper {
        overflow: auto;
        max-height: 80vh;
        border: 1px solid #dee2e6;
        background: #fff;
    }
    #sheet-table {
        border-collapse: collapse;
        font-size: 0.82em;
        white-space: nowrap;
        width: auto;
    }
    #sheet-table thead th {
        position: sticky;
        top: 0;
        background: #343a40;
        color: #fff;
        padding: 6px 12px;
        border: 1px solid #495057;
        font-weight: 600;
    }
    #sheet-table tbody td {
        padding: 4px 10px;
        border: 1px solid #dee2e6;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #sheet-table tbody tr:nth-child(even) { background: #f8f9fa; }
    #sheet-table tbody tr:hover { background: #e9f0ff; }
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
        <div id="excel-loading" class="text-center p-3">Loading spreadsheet...</div>
        <div id="sheet-wrapper" style="display:none;">
            <table id="sheet-table"><thead></thead><tbody></tbody></table>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/laravel-file-viewer/officetohtml/SheetJS/xlsx.mini.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileUrl = @json($fileUrl);
    var workbook = null;
    var ROW_LIMIT = 2000;

    function renderSheet(ws) {
        var data = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
        var thead = document.querySelector('#sheet-table thead');
        var tbody = document.querySelector('#sheet-table tbody');
        thead.innerHTML = '';
        tbody.innerHTML = '';

        if (!data.length) return;

        var headerRow = document.createElement('tr');
        (data[0] || []).forEach(function (cell) {
            var th = document.createElement('th');
            th.textContent = cell;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);

        var rows = data.slice(1);
        var truncated = rows.length > ROW_LIMIT;
        if (truncated) rows = rows.slice(0, ROW_LIMIT);

        var frag = document.createDocumentFragment();
        rows.forEach(function (row) {
            var tr = document.createElement('tr');
            row.forEach(function (cell) {
                var td = document.createElement('td');
                td.textContent = (cell !== null && cell !== undefined) ? String(cell) : '';
                tr.appendChild(td);
            });
            frag.appendChild(tr);
        });
        tbody.appendChild(frag);

        if (truncated) {
            var notice = document.createElement('tr');
            notice.innerHTML = '<td colspan="' + (data[0] || []).length + '" class="text-center text-warning fw-bold py-2">' +
                'Showing first ' + ROW_LIMIT + ' rows of ' + (data.length - 1) + ' total.</td>';
            tbody.appendChild(notice);
        }

        document.getElementById('excel-loading').style.display = 'none';
        document.getElementById('sheet-wrapper').style.display = '';
    }

    function buildTabs(wb) {
        var tabsContainer = document.getElementById('sheet-tabs');
        tabsContainer.innerHTML = '';
        wb.SheetNames.forEach(function (name, index) {
            var btn = document.createElement('button');
            btn.className = 'sheet-tab-btn' + (index === 0 ? ' active' : '');
            btn.textContent = name;
            btn.addEventListener('click', function () {
                tabsContainer.querySelectorAll('.sheet-tab-btn').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                renderSheet(wb.Sheets[name]);
            });
            tabsContainer.appendChild(btn);
        });
    }

    fetch(fileUrl)
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.arrayBuffer();
        })
        .then(function (buf) {
            workbook = XLSX.read(buf, { type: 'array' });
            buildTabs(workbook);
            if (workbook.SheetNames.length > 0) {
                renderSheet(workbook.Sheets[workbook.SheetNames[0]]);
            } else {
                document.getElementById('excel-loading').textContent = 'No sheets found.';
            }
        })
        .catch(function (err) {
            document.getElementById('excel-loading').innerHTML =
                '<div class="alert alert-danger">Failed to load spreadsheet: ' + err.message + '</div>';
        });
});
</script>

@endsection
