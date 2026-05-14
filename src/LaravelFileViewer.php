<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\Facades\Storage;

class LaravelFileViewer
{
    public static function show(string $fileName, string $filePath, string $fileUrl, string $disk = 'public', array $fileData = []): \Illuminate\Contracts\View\View
    {
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
                return view('laravel-file-viewer::previewFileText', $viewdata);
            case 'application':
                if ($subtype === 'vnd.openxmlformats-officedocument.wordprocessingml.document' || $subtype === 'msword') {
                    return view('laravel-file-viewer::previewFileDocxjs', $viewdata);
                }
                if ($subtype === 'zip' || $subtype === 'x-zip-compressed') {
                    return view('laravel-file-viewer::previewFileDetails', $viewdata);
                }
                if ($subtype === 'json') {
                    return view('laravel-file-viewer::previewFileText', $viewdata);
                }
                return view('laravel-file-viewer::previewFileOffice', $viewdata);
            default:
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
                    case 'zip':
                    case 'x-zip-compressed': return 'fa-solid fa-file-zipper';
                    case 'json': return 'fa-solid fa-file-code';
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
