@extends('laravel-file-viewer::layouts.blank_app_no_logo')
@section('content')
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
        <pre id="text-preview" class="language-plaintext" style="background:#f8f9fa;padding:1em;"></pre>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>
    fetch(@json($fileUrl))
        .then(response => response.text())
        .then(text => {
            const preElement = document.getElementById('text-preview');
            const fileExtension = @json(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            const language = Prism.languages[fileExtension] ? fileExtension : 'plaintext'; // Default to plaintext if unsupported

            preElement.className = `language-${language}`;
            preElement.textContent = text; // Escape HTML to prevent XSS
            Prism.highlightElement(preElement); // Highlight the content
        });
</script>
@endsection