<?php
    $page_title=$fileName;
    ?>
    @extends('laravel-file-viewer::layouts.blank_app_no_logo')

    @section('content')

<div class="row">
<div class="col-md-12">
    <div class="card m-0">
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
    <div id="csv-loading" class="text-center p-3">Loading CSV...</div>
    <div id="csv-notice" class="alert alert-warning" style="display:none;"></div>
    <div style="overflow:auto; max-height:85vh;">
        <table id="csv-table" class="table table-bordered table-sm table-striped" style="display:none;font-size:0.85em;"></table>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ROW_LIMIT = 5000;

    function parseCSV(text) {
        const rows = [];
        let row = [], field = '', inQuotes = false, i = 0;
        while (i < text.length) {
            const ch = text[i];
            if (inQuotes) {
                if (ch === '"' && text[i+1] === '"') { field += '"'; i += 2; continue; }
                if (ch === '"') { inQuotes = false; i++; continue; }
                field += ch;
            } else {
                if (ch === '"') { inQuotes = true; i++; continue; }
                if (ch === ',') { row.push(field); field = ''; i++; continue; }
                if (ch === '\r' && text[i+1] === '\n') { row.push(field); rows.push(row); row = []; field = ''; i += 2; continue; }
                if (ch === '\n' || ch === '\r') { row.push(field); rows.push(row); row = []; field = ''; i++; continue; }
                field += ch;
            }
            i++;
        }
        if (field || row.length) { row.push(field); rows.push(row); }
        // Remove trailing empty row if any
        if (rows.length && rows[rows.length-1].every(f => f === '')) rows.pop();
        return rows;
    }

    fetch(@json($fileUrl))
        .then(r => r.text())
        .then(text => {
            const allRows = parseCSV(text);
            if (!allRows.length) { document.getElementById('csv-loading').textContent = 'CSV file is empty.'; return; }

            const truncated = allRows.length - 1 > ROW_LIMIT; // -1 for header
            const rows = truncated ? allRows.slice(0, ROW_LIMIT + 1) : allRows;

            if (truncated) {
                const notice = document.getElementById('csv-notice');
                notice.style.display = '';
                notice.textContent = `Showing first ${ROW_LIMIT.toLocaleString()} of ${(allRows.length - 1).toLocaleString()} rows.`;
            }

            const table = document.getElementById('csv-table');
            const header = rows[0];
            const thead = document.createElement('thead');
            thead.className = 'thead-dark';
            const hRow = document.createElement('tr');
            header.forEach(cell => {
                const th = document.createElement('th');
                th.textContent = cell;
                hRow.appendChild(th);
            });
            thead.appendChild(hRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            rows.slice(1).forEach(row => {
                const tr = document.createElement('tr');
                row.forEach(cell => {
                    const td = document.createElement('td');
                    td.textContent = cell;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);

            document.getElementById('csv-loading').style.display = 'none';
            table.style.display = '';
        })
        .catch(() => {
            document.getElementById('csv-loading').textContent = 'Failed to load CSV file.';
        });
});
</script>
@endsection
