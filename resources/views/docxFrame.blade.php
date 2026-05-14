<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body { margin: 0; padding: 0; background: #525659; }
        #loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: sans-serif;
            color: #ccc;
            font-size: 14px;
        }
        .docx-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 24px 0;
        }
        section.docx {
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div id="loading">Rendering document…</div>
    <div id="content"></div>

    <script src="{{ asset('vendor/laravel-file-viewer/docx-preview/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-file-viewer/docx-preview/docx-preview.min.js') }}"></script>
    <script>
    (function () {
        var url = @json($fileUrl);
        var container = document.getElementById('content');
        var loading   = document.getElementById('loading');

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
                    inWrapper: true,
                    ignoreWidth: false,
                    ignoreHeight: false,
                    ignoreFonts: false,
                    breakPages: true,
                    renderHeaders: true,
                    renderFooters: true,
                    renderFootnotes: true,
                    renderEndnotes: true,
                    useBase64URL: true,
                    experimental: false,
                    debug: false,
                });
            })
            .then(function () {
                loading.style.display = 'none';
            })
            .catch(function (err) {
                loading.textContent = 'Failed to render: ' + err.message;
                loading.style.color = '#f88';
            });
    })();
    </script>
</body>
</html>
