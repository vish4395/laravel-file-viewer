<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class LaravelFileViewer
{
    public static function show(string $fileName, string $filePath, string $fileUrl, ?string $disk = null, array $fileData = []): View
    {
        $disk    = $disk ?? config('laravel-file-viewer.default_disk', 'public');
        $storage = Storage::disk($disk);

        if (!$storage->exists($filePath)) {
            abort(404, __('file_not_found_or_deleted'));
        }

        $type    = $storage->mimeType($filePath);
        $metadata = ['size' => $storage->size($filePath)];
        $iconClass           = self::getIconClass($type);
        $filesizebyteformat  = self::formatBytes($metadata['size']);
        $viewdata = compact('fileName', 'fileUrl', 'type', 'fileData', 'metadata', 'iconClass', 'filesizebyteformat');

        // Strip MIME parameters (e.g. "text/plain; charset=UTF-8" → "text/plain")
        [$mainType, $rawSubtype] = explode('/', $type, 2) + ['', ''];
        $subtype = strtok($rawSubtype, ';');

        switch ($mainType) {
            case 'image':
                return view('laravel-file-viewer::previewFileImage', $viewdata);
            case 'audio':
                return view('laravel-file-viewer::previewFileAudio', $viewdata);
            case 'video':
                return view('laravel-file-viewer::previewFileVideo', $viewdata);
            case 'text':
                if ($subtype === 'csv') {
                    return view('laravel-file-viewer::previewFileCsv', $viewdata);
                }
                return view('laravel-file-viewer::previewFileText', $viewdata);
            case 'application':
                switch ($subtype) {
                    case 'pdf':
                        return view('laravel-file-viewer::previewFilePdf', $viewdata);
                    case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                    case 'msword':
                        return view('laravel-file-viewer::previewFileDocxjs', $viewdata);
                    case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    case 'vnd.ms-excel':
                        return view('laravel-file-viewer::previewFileExcel', $viewdata);
                    case 'vnd.openxmlformats-officedocument.presentationml.presentation':
                    case 'vnd.ms-powerpoint':
                        return view('laravel-file-viewer::previewFilePptx', $viewdata);
                    case 'vnd.oasis.opendocument.text':
                    case 'vnd.oasis.opendocument.spreadsheet':
                    case 'vnd.oasis.opendocument.presentation':
                        return view('laravel-file-viewer::previewFileOdf', $viewdata);
                    case 'zip':
                    case 'x-zip-compressed':
                    case 'x-rar-compressed':
                    case 'vnd.rar':
                    case 'x-tar':
                    case 'gzip':
                    case 'x-gzip':
                        return view('laravel-file-viewer::previewFileDetails', $viewdata);
                    case 'json':
                        return view('laravel-file-viewer::previewFileText', $viewdata);
                    default:
                        return self::fallbackView($viewdata);
                }
            default:
                return self::fallbackView($viewdata);
        }
    }

    private static function fallbackView(array $viewdata): View
    {
        if (config('laravel-file-viewer.google_viewer_fallback', false)) {
            return view('laravel-file-viewer::previewFileGoogle', $viewdata);
        }
        return view('laravel-file-viewer::previewFileOffice', $viewdata);
    }

    public static function getIconClass(string $type): string
    {
        [$mainType, $rawSubtype] = explode('/', $type, 2) + ['', ''];
        $subtype = strtok($rawSubtype, ';');

        return match ($mainType) {
            'image' => 'fa-solid fa-file-image',
            'video' => 'fa-solid fa-file-video',
            'audio' => 'fa-solid fa-file-audio',
            'text'  => match (true) {
                $subtype === 'csv'  => 'fa-solid fa-file-csv',
                $subtype === 'json' => 'fa-solid fa-file-code',
                default             => 'fa-solid fa-file-lines',
            },
            'application' => match ($subtype) {
                'pdf'                                                              => 'fa-solid fa-file-pdf',
                'vnd.openxmlformats-officedocument.wordprocessingml.document',
                'msword', 'rtf',
                'vnd.oasis.opendocument.text'                                     => 'fa-solid fa-file-word',
                'vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'vnd.ms-excel',
                'vnd.oasis.opendocument.spreadsheet'                              => 'fa-solid fa-file-excel',
                'vnd.openxmlformats-officedocument.presentationml.presentation',
                'vnd.ms-powerpoint',
                'vnd.oasis.opendocument.presentation'                             => 'fa-solid fa-file-powerpoint',
                'zip', 'x-zip-compressed',
                'x-rar-compressed', 'vnd.rar',
                'x-tar', 'gzip', 'x-gzip'                                        => 'fa-solid fa-file-zipper',
                'json'                                                             => 'fa-solid fa-file-code',
                default                                                            => 'fa-solid fa-file',
            },
            default => 'fa-solid fa-file',
        };
    }

    public static function formatBytes(int|float $size, int $precision = 2): string
    {
        if ($size <= 0) {
            return '0 bytes';
        }
        $base     = log((float) $size) / log(1024);
        $index    = (int) min(floor($base), 4); // cap at TB
        $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[$index];
    }
}
