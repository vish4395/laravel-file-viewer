<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\Facades\Storage;

class LaravelFileViewer
{
    public static function show(String $fileName, String $filePath, String $fileUrl, $disk = 'public', $fileData = [])
    {
      if (!Storage::disk('public')->exists($filePath)) {
          return response()->json(['error' => 'File not found.'], 404);
      }

      if (!Storage::disk($disk)->exists($filePath)) {
        abort(404,__("file_not_found_or_deleted"));
      }
      $type = Storage::disk($disk)->mimeType($filePath);
      $metadata = [
        'size' => Storage::disk($disk)->size($filePath)
      ];
      $iconClass=self::getIconClass($type);
      $filesizenyteformat=self::formatBytes($metadata['size']);
      $viewdata=compact('fileName','fileUrl','type','fileData','metadata','iconClass','filesizenyteformat');

      switch (explode('/',$type)[0]) {
          case 'image':
              return view('laravel-file-viewer::previewFileImage',$viewdata);
              break;
          case 'audio':
              return view('laravel-file-viewer::previewFileAudio',$viewdata);
              break;
          case 'video':
              return view('laravel-file-viewer::previewFileVideo',$viewdata);
              break;
          case 'application':
              // Use docxjs view for docx files
              $subtype = explode('/',$type)[1];
              if (
                  $subtype === 'vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                  $subtype === 'msword'
              ) {
                  return view('laravel-file-viewer::previewFileDocxjs', $viewdata);
              }
              // You can add more custom application/* handlers here if needed
              // fallback to office view
              return view('laravel-file-viewer::previewFileOffice', $viewdata);
              break;
          default:
              return view('laravel-file-viewer::previewFileOffice',$viewdata);
              break;
      }
    }

    public static function getIconClass($type){
        switch (explode('/',$type)[0]) {
            case 'image':
                return 'fa-solid fa-file-image';
                break;
            
            case 'video':
                return 'fa-solid fa-file-video';
                break;
            
            case 'audio':
                return 'fa-solid fa-file-audio';
                break;
            
            case 'application':
                switch (explode('/',$type)[1]) {
                  case 'pdf':
                    return 'fa-solid fa-file-pdf';
                    break;
                  case 'vnd.openxmlformats-officedocument.presentationml.presentation':
                    return 'fa-solid fa-file-powerpoint';
                    break;
                
                  case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return 'fa-solid fa-file-word';
                    break;
                
                  case 'msword':
                    return 'fa-solid fa-file-word';
                    break;
                
                  case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    return 'fa-solid fa-file-excel';
                    return 'fa-solid fa-file-spreadsheet';
                    break;
  
                  default:
                    return 'fa-solid fa-file';
                    break;
                }
                break;
            
            default:
            return 'fa-solid fa-file';
                break;
        }
       }
    static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}
