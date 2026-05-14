<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\Facades\Storage;

class LaravelFileViewer
{
    public static function show(string $fileName, string $filePath, string $fileUrl, string $disk = null, array $fileData = []): \Illuminate\Contracts\View\View
    {
        $disk = $disk ?? config('laravel-file-viewer.default_disk', 'public');
        $storage = Storage::disk($disk);

        if (!$storage->exists($filePath)) {
            abort(404, __("file_not_found_or_deleted"));
        }

        $type = $storage->mimeType($filePath);
        $metadata = ['size' => $storage->size($filePath)];
        $iconClass = self::getIconClass($type);
        $filesizebyteformat = self::formatBytes($metadata['size']);
        $viewdata = compact('fileName', 'fileUrl', 'type', 'fileData', 'metadata', 'iconClass', 'filesizebyteformat');

        [$mainType, $subtype] = explode('/', $type, 2) + ['', ''];

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
                        if (config('laravel-file-viewer.google_viewer_fallback', false)) {
                            return view('laravel-file-viewer::previewFileGoogle', $viewdata);
                        }
                        return view('laravel-file-viewer::previewFileOffice', $viewdata);
                }
            default:
                if (config('laravel-file-viewer.google_viewer_fallback', false)) {
                    return view('laravel-file-viewer::previewFileGoogle', $viewdata);
                }
                return view('laravel-file-viewer::previewFileOffice', $viewdata);
        }
    }

    public static function getIconClass(string $type): string
    {
        [$mainType, $subtype] = explode('/', $type, 2) + ['', ''];

        switch ($mainType) {
            case 'image': return 'fa-solid fa-file-image';
            case 'video': return 'fa-solid fa-file-video';
            case 'audio': return 'fa-solid fa-file-audio';
            case 'text':
                if ($subtype === 'csv') return 'fa-solid fa-file-csv';
                if ($subtype === 'json') return 'fa-solid fa-file-code';
                return 'fa-solid fa-file-lines';
            case 'application':
                switch ($subtype) {
                    case 'pdf': return 'fa-solid fa-file-pdf';
                    case 'vnd.openxmlformats-officedocument.presentationml.presentation': return 'fa-solid fa-file-powerpoint';
                    case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                    case 'msword': return 'fa-solid fa-file-word';
                    case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet': return 'fa-solid fa-file-excel';
                    case 'vnd.ms-excel': return 'fa-solid fa-file-excel';
                    case 'vnd.ms-powerpoint': return 'fa-solid fa-file-powerpoint';
                    case 'zip':
                    case 'x-zip-compressed': return 'fa-solid fa-file-zipper';
                    case 'x-rar-compressed':
                    case 'vnd.rar': return 'fa-solid fa-file-zipper';
                    case 'x-tar':
                    case 'gzip':
                    case 'x-gzip': return 'fa-solid fa-file-zipper';
                    case 'json': return 'fa-solid fa-file-code';
                    case 'rtf': return 'fa-solid fa-file-word';
                    case 'vnd.oasis.opendocument.text': return 'fa-solid fa-file-word';
                    case 'vnd.oasis.opendocument.spreadsheet': return 'fa-solid fa-file-excel';
                    case 'vnd.oasis.opendocument.presentation': return 'fa-solid fa-file-powerpoint';
                    default: return 'fa-solid fa-file';
                }
            default:
                return 'fa-solid fa-file';
        }
    }

    public static function formatBytes(int|float $size, int $precision = 2): string
    {
        if ($size <= 0) {
            return '0 bytes';
        }
        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }
}
