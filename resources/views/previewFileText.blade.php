<?php
$page_title   = $fileName;
$defaultTheme = config('laravel-file-viewer.code_theme', 'prism-tomorrow');
$themes = [
    'prism'              => 'Default (Light)',
    'prism-coy'          => 'Coy',
    'prism-solarizedlight' => 'Solarized Light',
    'prism-funky'        => 'Funky',
    'prism-dark'         => 'Dark',
    'prism-okaidia'      => 'Okaidia',
    'prism-twilight'     => 'Twilight',
    'prism-tomorrow'     => 'Tomorrow Night',
    'prism-atom-dark'    => 'Atom Dark',
    'prism-dracula'      => 'Dracula',
    'prism-material-dark' => 'Material Dark',
    'prism-nord'         => 'Nord',
    'prism-one-dark'     => 'One Dark',
    'prism-vsc-dark-plus' => 'VS Code Dark+',
];
?>
@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@push('styles')
<link id="prism-theme-css"
      rel="stylesheet"
      href="{{ asset('vendor/laravel-file-viewer/prism/themes/' . $defaultTheme . '.min.css') }}"
      data-base-url="{{ asset('vendor/laravel-file-viewer/prism/themes/') }}">
<style>
    #format-bar { display: none; }
    #format-bar.visible { display: flex; }
    #theme-select {
        appearance: none;
        -webkit-appearance: none;
        padding: 3px 24px 3px 8px;
        font-size: 11px;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E") no-repeat right 7px center;
        color: #475569;
        cursor: pointer;
        outline: none;
        max-width: 140px;
    }
    #theme-select:focus { border-color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="flex flex-col h-screen">
    @include('laravel-file-viewer::previewFileDetails')

    <div class="flex-1 overflow-auto bg-white relative flex flex-col">
        <div id="format-bar" class="sticky top-0 z-10 bg-slate-50 border-b border-slate-100 px-4 py-1.5 items-center gap-2 shrink-0">
            <span id="lang-badge" class="text-xs font-mono text-slate-400 shrink-0"></span>

            <div class="flex items-center gap-1.5 ml-auto">
                <button id="format-btn"
                        class="hidden items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-md bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Format
                </button>
                <label class="text-xs text-slate-400 shrink-0">Theme</label>
                <select id="theme-select" title="Syntax highlight theme">
                    @foreach($themes as $value => $label)
                        <option value="{{ $value }}"
                            {{ $value === $defaultTheme ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <pre id="text-preview" class="language-plaintext flex-1 m-0 text-sm leading-relaxed p-4" style="min-height:0;overflow:auto;"></pre>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/laravel-file-viewer/prism/prism.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/prism/prism-autoloader.min.js') }}"
        data-autoloader-path="{{ asset('vendor/laravel-file-viewer/prism/components/') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/jsbeautify/beautify.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/jsbeautify/beautify-html.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-file-viewer/jsbeautify/beautify-css.min.js') }}"></script>
<script>
(function () {
    var LS_KEY      = 'lfv_code_theme';
    var FORMATTABLE = { json: true, js: true, html: true, htm: true, css: true, xml: true };
    var BEAUTIFY_OPTS = { indent_size: 2, wrap_line_length: 120, end_with_newline: true };

    var ext       = @json(strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION)));
    var pre       = document.getElementById('text-preview');
    var bar       = document.getElementById('format-bar');
    var btn       = document.getElementById('format-btn');
    var badge     = document.getElementById('lang-badge');
    var select    = document.getElementById('theme-select');
    var themeLink = document.getElementById('prism-theme-css');
    var baseUrl   = themeLink.dataset.baseUrl;

    var rawText   = '';
    var formatted = false;

    // ── Theme ──────────────────────────────────────────────────────────────
    function applyTheme(name) {
        themeLink.href = baseUrl.replace(/\/$/, '') + '/' + name + '.min.css';
        select.value   = name;
        localStorage.setItem(LS_KEY, name);
    }

    // Restore from localStorage if set; option exists in select
    var saved = localStorage.getItem(LS_KEY);
    if (saved && select.querySelector('option[value="' + saved + '"]')) {
        applyTheme(saved);
    }

    select.addEventListener('change', function () {
        applyTheme(this.value);
        // Re-highlight because some themes need DOM-level class re-evaluation
        if (pre.textContent) { Prism.highlightElement(pre); }
    });

    // ── Formatter ──────────────────────────────────────────────────────────
    function applyText(text) {
        pre.className = 'language-' + (ext || 'plaintext') + ' flex-1 m-0 text-sm leading-relaxed p-4';
        pre.textContent = text;
        Prism.highlightElement(pre);
    }

    function formatText(text) {
        try {
            if (ext === 'json') { return JSON.stringify(JSON.parse(text), null, 2); }
            if (ext === 'js')   { return js_beautify(text, BEAUTIFY_OPTS); }
            if (ext === 'html' || ext === 'htm' || ext === 'xml') {
                return html_beautify(text, BEAUTIFY_OPTS);
            }
            if (ext === 'css') { return css_beautify(text, BEAUTIFY_OPTS); }
        } catch (e) {}
        return text;
    }

    btn.addEventListener('click', function () {
        formatted = !formatted;
        applyText(formatted ? formatText(rawText) : rawText);
        btn.innerHTML = formatted
            ? '<i class="fa-solid fa-code"></i> Raw'
            : '<i class="fa-solid fa-wand-magic-sparkles"></i> Format';
    });

    // ── Fetch & render ─────────────────────────────────────────────────────
    fetch(@json($fileUrl))
        .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
        .then(function (text) {
            rawText = text;
            applyText(text);

            bar.classList.add('visible');
            badge.textContent = ext ? ext.toUpperCase() : '';

            if (FORMATTABLE[ext]) {
                btn.classList.remove('hidden');
                btn.classList.add('inline-flex');
            }
        })
        .catch(function (err) {
            pre.textContent = 'Failed to load file: ' + err.message;
        });
})();
</script>
@endpush
