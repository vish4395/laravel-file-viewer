@extends('laravel-file-viewer::layouts.blank_app_no_logo')

@section('content')
<style>
    .file-detail-card {
        width: 100%;
        z-index: 999;
        background: #ffffffed;
    }

    /* Scrollable page container */
    #docxjs-viewer {
        background: #f0f0f0;
        padding: 24px 0;
        min-height: 80vh;
        overflow: auto;
    }

    /*
     * Bootstrap ships `*, *::before, *::after { box-sizing: border-box }`.
     * docx-preview computes page widths from Word's inch values and expects
     * content-box — border-box shrinks the content area by the padding, causing
     * text to overflow and clip. Restore content-box for everything inside.
     */
    #docxjs-viewer .docx-wrapper,
    #docxjs-viewer .docx-wrapper * {
        box-sizing: content-box;
    }

    /*
     * Bootstrap sets margin-bottom: 1rem on p and overrides heading sizes/weights.
     * These fight Word's own spacing. Revert only these specific properties so
     * docx-preview's injected per-element styles take effect.
     */
    #docxjs-viewer p {
        margin-top: 0;
        margin-bottom: 0;
    }
    #docxjs-viewer h1, #docxjs-viewer h2, #docxjs-viewer h3,
    #docxjs-viewer h4, #docxjs-viewer h5, #docxjs-viewer h6 {
        margin-top: 0;
        margin-bottom: 0;
        font-size: inherit;
        font-weight: inherit;
        line-height: inherit;
        color: inherit;
    }
    #docxjs-viewer ul, #docxjs-viewer ol {
        padding-left: 0;
        margin: 0;
    }
    #docxjs-viewer table {
        border-collapse: revert;
    }

    /* Page shadow — applied on top of docx-preview's .docx-wrapper layout */
    #docxjs-viewer section.docx {
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
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
        <div id="docxjs-loading" class="text-center p-4">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2 text-muted">Rendering document…</div>
        </div>
        <div id="docxjs-viewer" style="display:none;"></div>
    </div>
</div>

{{-- JSZip 3.x (required by docx-preview) --}}
<script src="{{ asset('vendor/laravel-file-viewer/docx-preview/jszip.min.js') }}"></script>
{{-- docx-preview: renders Word documents to HTML faithfully --}}
<script src="{{ asset('vendor/laravel-file-viewer/docx-preview/docx-preview.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var url = @json($fileUrl);
    var container = document.getElementById('docxjs-viewer');
    var loading  = document.getElementById('docxjs-loading');

    /*
     * styleContainer: a separate DOM node where docx-preview injects the
     * Word theme CSS (--docx-accent1-color, fonts, etc.). Keeping it in
     * <head> lets the custom properties cascade into the rendered pages.
     */
    var styleContainer = document.createElement('div');
    document.head.appendChild(styleContainer);

    fetch(url)
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.blob();
        })
        .then(function (blob) {
            return window.docx.renderAsync(blob, container, styleContainer, {
                className: 'docx',
                inWrapper: true,         // wrap each page in a page-like section
                ignoreWidth: false,      // honour Word's page width
                ignoreHeight: false,     // honour page height (allows proper page breaks)
                ignoreFonts: false,      // use document fonts where available
                breakPages: true,        // render page breaks between sections
                renderHeaders: true,
                renderFooters: true,
                renderFootnotes: true,
                renderEndnotes: true,
                useBase64URL: true,      // inline images as base64 (no separate requests)
                experimental: false,
                debug: false,
            });
        })
        .then(function () {
            loading.style.display = 'none';
            container.style.display = '';
        })
        .catch(function (err) {
            loading.innerHTML =
                '<div class="alert alert-danger">Failed to render document: ' + err.message + '</div>';
        });
});
</script>

@endsection
