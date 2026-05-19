<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<style>
    #csv-table thead th {
        background: #1e293b;
        color: #f1f5f9;
        padding: 8px 14px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #0f172a;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    #csv-table tbody td {
        padding: 6px 14px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
        white-space: nowrap;
        color: #334155;
    }
    #csv-table tbody tr:nth-child(even) td { background: #f8fafc; }
    #csv-table tbody tr:hover td { background: #eff6ff; }
</style>

<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-auto bg-white">
        <div id="csv-loading" class="flex items-center justify-center gap-3 text-slate-400 py-16">
            <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm">Loading CSV…</span>
        </div>
        <div id="csv-notice" class="hidden mx-4 mt-3 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700"></div>
        <table id="csv-table" class="hidden w-full border-collapse"></table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ROW_LIMIT = 5000;

    function parseCSV(text) {
        const rows = [];
        let row = [], field = '', inQuotes = false, i = 0;
        while (i < text.length) {
            const ch = text[i];
            if (inQuotes) {
                if (ch === '"' && text[i + 1] === '"') { field += '"'; i += 2; continue; }
                if (ch === '"') { inQuotes = false; i++; continue; }
                field += ch;
            } else {
                if (ch === '"') { inQuotes = true; i++; continue; }
                if (ch === ',') { row.push(field); field = ''; i++; continue; }
                if (ch === '\r' && text[i + 1] === '\n') { row.push(field); rows.push(row); row = []; field = ''; i += 2; continue; }
                if (ch === '\n' || ch === '\r') { row.push(field); rows.push(row); row = []; field = ''; i++; continue; }
                field += ch;
            }
            i++;
        }
        if (field || row.length) { row.push(field); rows.push(row); }
        if (rows.length && rows[rows.length - 1].every(function (f) { return f === ''; })) rows.pop();
        return rows;
    }

    fetch(@json($fileUrl))
        .then(function (r) { return r.text(); })
        .then(function (text) {
            const allRows = parseCSV(text);
            if (!allRows.length) {
                document.getElementById('csv-loading').textContent = 'CSV file is empty.';
                return;
            }

            const truncated = allRows.length - 1 > ROW_LIMIT;
            const rows = truncated ? allRows.slice(0, ROW_LIMIT + 1) : allRows;

            if (truncated) {
                const notice = document.getElementById('csv-notice');
                notice.classList.remove('hidden');
                notice.textContent = 'Showing first ' + ROW_LIMIT.toLocaleString() + ' of ' + (allRows.length - 1).toLocaleString() + ' rows.';
            }

            const table = document.getElementById('csv-table');

            const thead = document.createElement('thead');
            const hRow  = document.createElement('tr');
            rows[0].forEach(function (cell) {
                const th = document.createElement('th');
                th.textContent = cell;
                hRow.appendChild(th);
            });
            thead.appendChild(hRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            rows.slice(1).forEach(function (row) {
                const tr = document.createElement('tr');
                row.forEach(function (cell) {
                    const td = document.createElement('td');
                    td.textContent = cell;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);

            document.getElementById('csv-loading').style.display = 'none';
            table.classList.remove('hidden');
        })
        .catch(function () {
            document.getElementById('csv-loading').textContent = 'Failed to load CSV file.';
        });
});
</script>
@endsection
