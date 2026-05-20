<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body { margin: 0; padding: 0; background: #f8f8f8; }
        #loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: sans-serif;
            color: #888;
            font-size: 14px;
        }
        #canvas {
            padding: 24px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div id="loading">Rendering document…</div>
    <div id="canvas"></div>

    <script>
    (function () {
        var fileUrl = @json($fileUrl);
        var script  = document.createElement('script');
        script.src  = '{{ asset('vendor/laravel-file-viewer/webodf/webodf.js') }}';
        script.type = 'text/javascript';

        script.onload = function () {
            runtime.loadClass('odf.OdfCanvas');

            var canvas    = document.getElementById('canvas');
            var odfCanvas = new odf.OdfCanvas(canvas);

            odfCanvas.addListener('statereadychange', function () {
                document.getElementById('loading').style.display = 'none';
            });

            odfCanvas.load(fileUrl);
        };

        script.onerror = function () {
            var el       = document.getElementById('loading');
            el.textContent = 'Failed to load viewer.';
            el.style.color = '#f88';
        };

        document.head.appendChild(script);
    }());
    </script>
</body>
</html>
