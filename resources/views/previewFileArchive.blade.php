<?php $page_title = $fileName; ?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<style>
    #archive-tree li { list-style: none; }
    #archive-tree .entry { display: flex; align-items: center; gap: 8px; padding: 4px 8px; border-radius: 4px; font-size: 13px; color: #334155; cursor: default; }
    #archive-tree .entry:hover { background: #f1f5f9; }
    #archive-tree .entry.is-dir { font-weight: 600; color: #1e293b; }
    #archive-tree .entry .size { margin-left: auto; font-size: 11px; color: #94a3b8; white-space: nowrap; }
    #archive-tree .children { padding-left: 20px; border-left: 1px solid #e2e8f0; margin-left: 12px; }
    #archive-search { width: 100%; box-sizing: border-box; padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; outline: none; }
    #archive-search:focus { border-color: #94a3b8; }
    .stat-pill { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; background: #f1f5f9; border-radius: 20px; font-size: 11px; color: #64748b; }
</style>
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-auto bg-white">
        <div id="archive-loading" class="flex items-center justify-center gap-3 text-slate-400 py-16">
            <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm">Reading archive…</span>
        </div>

        <div id="archive-content" class="hidden p-4">
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <span id="stat-files" class="stat-pill"><i class="fa-solid fa-file text-slate-400"></i> <span></span></span>
                <span id="stat-dirs" class="stat-pill"><i class="fa-solid fa-folder text-yellow-500"></i> <span></span></span>
                <span id="stat-size" class="stat-pill"><i class="fa-solid fa-weight-hanging text-slate-400"></i> <span></span></span>
            </div>
            <div class="mb-3">
                <input id="archive-search" type="text" placeholder="Filter files…" autocomplete="off">
            </div>
            <ul id="archive-tree" class="m-0 p-0"></ul>
        </div>

        <div id="archive-unsupported" class="hidden flex flex-col items-center justify-center gap-4 py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                <i class="{{ $iconClass }} text-3xl text-slate-400"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-700 mb-1">Archive contents cannot be listed</p>
                <p class="text-xs text-slate-400">RAR and TAR archives require server-side extraction.</p>
            </div>
            <a href="{{ $fileUrl }}" download="{{ $fileName }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fa-solid fa-download"></i> Download File
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/docx-preview/jszip.min.js') }}"></script>
<script>
(function () {
    var ext = @json(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)));
    var isZip = (ext === 'zip');

    if (!isZip) {
        document.getElementById('archive-loading').style.display = 'none';
        document.getElementById('archive-unsupported').classList.remove('hidden');
        return;
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        var k = 1024, sizes = ['B', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[Math.min(i, sizes.length - 1)];
    }

    function buildTree(files) {
        var root = {};
        files.forEach(function (f) {
            var parts = f.name.replace(/\/$/, '').split('/');
            var node = root;
            parts.forEach(function (part, idx) {
                if (!node[part]) {
                    node[part] = { __meta: { isDir: idx < parts.length - 1 || f.dir, size: f.size, date: f.date } };
                }
                node = node[part];
            });
        });
        return root;
    }

    function renderTree(node, query) {
        var ul = document.createElement('ul');
        ul.className = 'archive-node m-0 p-0';
        var keys = Object.keys(node).filter(function (k) { return k !== '__meta'; }).sort(function (a, b) {
            var aDir = node[a].__meta && node[a].__meta.isDir;
            var bDir = node[b].__meta && node[b].__meta.isDir;
            if (aDir && !bDir) return -1;
            if (!aDir && bDir) return 1;
            return a.localeCompare(b);
        });

        keys.forEach(function (key) {
            var child = node[key];
            var meta  = child.__meta || {};
            var isDir = meta.isDir || Object.keys(child).filter(function (k) { return k !== '__meta'; }).length > 0;

            if (query && !key.toLowerCase().includes(query)) return;

            var li  = document.createElement('li');
            var div = document.createElement('div');
            div.className = 'entry' + (isDir ? ' is-dir' : '');

            var icon = document.createElement('i');
            icon.className = isDir
                ? 'fa-solid fa-folder text-yellow-400 w-4 text-center shrink-0'
                : 'fa-solid fa-file text-slate-300 w-4 text-center shrink-0';

            var name = document.createElement('span');
            name.className = 'truncate';
            name.textContent = key;

            div.appendChild(icon);
            div.appendChild(name);

            if (!isDir && meta.size !== undefined) {
                var size = document.createElement('span');
                size.className = 'size';
                size.textContent = formatBytes(meta.size);
                div.appendChild(size);
            }

            li.appendChild(div);

            var childKeys = Object.keys(child).filter(function (k) { return k !== '__meta'; });
            if (childKeys.length) {
                var nested = renderTree(child, query);
                nested.className += ' children';
                li.appendChild(nested);
            }

            ul.appendChild(li);
        });
        return ul;
    }

    fetch(@json($fileUrl))
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.arrayBuffer();
        })
        .then(function (buf) {
            return JSZip.loadAsync(buf);
        })
        .then(function (zip) {
            var files = [];
            var totalSize = 0;
            var dirCount  = 0;
            var fileCount = 0;

            zip.forEach(function (path, entry) {
                var size = entry._data ? (entry._data.uncompressedSize || 0) : 0;
                files.push({ name: path, dir: entry.dir, size: size, date: entry.date });
                if (entry.dir) { dirCount++; } else { fileCount++; totalSize += size; }
            });

            var tree = buildTree(files);

            document.getElementById('archive-loading').style.display = 'none';
            document.getElementById('archive-content').classList.remove('hidden');

            document.querySelector('#stat-files span').textContent = fileCount + ' file' + (fileCount !== 1 ? 's' : '');
            document.querySelector('#stat-dirs span').textContent  = dirCount + ' folder' + (dirCount !== 1 ? 's' : '');
            document.querySelector('#stat-size span').textContent  = formatBytes(totalSize) + ' uncompressed';

            var treeEl = document.getElementById('archive-tree');
            treeEl.appendChild(renderTree(tree, ''));

            document.getElementById('archive-search').addEventListener('input', function () {
                var q = this.value.trim().toLowerCase();
                treeEl.innerHTML = '';
                treeEl.appendChild(renderTree(tree, q || ''));
            });
        })
        .catch(function (err) {
            var el = document.getElementById('archive-loading');
            el.textContent = 'Failed to read archive: ' + err.message;
        });
})();
</script>
@endpush
