<?php

use Illuminate\Support\Facades\Route;

Route::get('/laravel-file-viewer/docx-frame', function () {
    $url = request()->query('url');
    abort_if(empty($url), 400);
    return view('laravel-file-viewer::docxFrame', ['fileUrl' => $url]);
})->name('laravel-file-viewer.docx-frame');
