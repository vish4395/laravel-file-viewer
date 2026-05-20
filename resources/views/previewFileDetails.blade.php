@php
$friendlyTypes = [
    'application/pdf'                                                              => 'PDF Document',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'     => 'Word Document',
    'application/msword'                                                           => 'Word Document',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'           => 'Excel Spreadsheet',
    'application/vnd.ms-excel'                                                     => 'Excel Spreadsheet',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation'   => 'PowerPoint',
    'application/vnd.ms-powerpoint'                                                => 'PowerPoint',
    'application/json'                                                             => 'JSON File',
    'application/zip'                                                              => 'ZIP Archive',
    'application/x-zip-compressed'                                                 => 'ZIP Archive',
    'application/x-rar-compressed'                                                 => 'RAR Archive',
    'application/vnd.rar'                                                          => 'RAR Archive',
    'application/x-tar'                                                            => 'TAR Archive',
    'text/plain'                                                                   => 'Text File',
    'text/csv'                                                                     => 'CSV File',
    'text/html'                                                                    => 'HTML File',
    'image/jpeg'                                                                   => 'JPEG Image',
    'image/png'                                                                    => 'PNG Image',
    'image/gif'                                                                    => 'GIF Image',
    'image/webp'                                                                   => 'WebP Image',
    'image/svg+xml'                                                                => 'SVG Image',
    'video/mp4'                                                                    => 'MP4 Video',
    'video/webm'                                                                   => 'WebM Video',
    'audio/mpeg'                                                                   => 'MP3 Audio',
    'audio/ogg'                                                                    => 'OGG Audio',
    'audio/wav'                                                                    => 'WAV Audio',
];

$cleanType   = strtok($type, ';');
$displayType = $friendlyTypes[$cleanType] ?? ucfirst(explode('/', $cleanType)[0]) . ' File';

$iconColorMap = [
    'fa-file-pdf'        => ['bg' => 'bg-red-50',     'text' => 'text-red-500'],
    'fa-file-word'       => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600'],
    'fa-file-excel'      => ['bg' => 'bg-green-50',   'text' => 'text-green-600'],
    'fa-file-powerpoint' => ['bg' => 'bg-orange-50',  'text' => 'text-orange-500'],
    'fa-file-image'      => ['bg' => 'bg-violet-50',  'text' => 'text-violet-500'],
    'fa-file-video'      => ['bg' => 'bg-purple-50',  'text' => 'text-purple-600'],
    'fa-file-audio'      => ['bg' => 'bg-amber-50',   'text' => 'text-amber-500'],
    'fa-file-csv'        => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
    'fa-file-code'       => ['bg' => 'bg-cyan-50',    'text' => 'text-cyan-600'],
    'fa-file-lines'      => ['bg' => 'bg-slate-100',  'text' => 'text-slate-500'],
    'fa-file-zipper'     => ['bg' => 'bg-yellow-50',  'text' => 'text-yellow-600'],
];

$badgeColors = ['bg' => 'bg-slate-100', 'text' => 'text-slate-500'];
foreach ($iconColorMap as $key => $colors) {
    if (str_contains($iconClass, $key)) {
        $badgeColors = $colors;
        break;
    }
}
@endphp

<header class="bg-white border-b border-slate-200 px-4 py-2.5 flex items-center gap-3 shrink-0">
    <div class="flex-shrink-0 w-9 h-9 rounded-lg {{ $badgeColors['bg'] }} {{ $badgeColors['text'] }} flex items-center justify-center">
        <i class="{{ $iconClass }} text-base"></i>
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-slate-800 truncate leading-tight">{{ $fileName }}</p>
        <div class="text-xs text-slate-400 truncate leading-tight mt-0.5">
            <span>{{ $displayType }}</span>
            <span class="mx-1">·</span>
            <span>{{ $filesizebyteformat }}</span>
            @foreach ($fileData as $fd)
                <span class="mx-1">·</span>
                <span>{{ $fd['label'] }}: {{ $fd['value'] }}</span>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-1 shrink-0">
        @isset($fileUrl)
            @if(config('laravel-file-viewer.toolbar.download', true))
                <a href="{{ $fileUrl }}"
                   download="{{ $fileName }}"
                   title="{{ __('Download') }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-download text-sm"></i>
                </a>
            @endif
            @if(config('laravel-file-viewer.toolbar.open_in_new_tab', true))
                <a href="{{ $fileUrl }}"
                   target="_blank"
                   rel="noopener"
                   title="{{ __('Open in new tab') }}"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                </a>
            @endif
            @if(config('laravel-file-viewer.toolbar.copy_link', true))
                <button data-copy-url="{{ $fileUrl }}"
                        title="{{ __('Copy link') }}"
                        onclick="var btn=this;navigator.clipboard.writeText(btn.dataset.copyUrl).then(function(){var t=btn.title;btn.title='Copied!';setTimeout(function(){btn.title=t},2000)})"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-link text-sm"></i>
                </button>
            @endif
        @endisset
        @if(config('laravel-file-viewer.toolbar.fullscreen', true))
            <button title="{{ __('Fullscreen') }}"
                    onclick="document.fullscreenElement?document.exitFullscreen():(document.documentElement.requestFullscreen||document.documentElement.webkitRequestFullscreen).call(document.documentElement)"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-expand text-sm"></i>
            </button>
        @endif
    </div>
</header>
