<?php

use Illuminate\Support\Facades\Route;

/*
 * Signed so the ?url= parameter cannot be tampered with or injected
 * by a third party. The signature is verified before the view is served.
 */
Route::get('/laravel-file-viewer/docx-frame', function () {
    $url = request()->query('url');
    abort_if(empty($url) || ! str_starts_with($url, 'http'), 400);
    return view('laravel-file-viewer::docxFrame', ['fileUrl' => $url]);
})->name('laravel-file-viewer.docx-frame')->middleware('signed');

Route::get('/laravel-file-viewer/odf-frame', function () {
    $url = request()->query('url');
    abort_if(empty($url) || ! str_starts_with($url, 'http'), 400);
    return view('laravel-file-viewer::odfFrame', ['fileUrl' => $url]);
})->name('laravel-file-viewer.odf-frame')->middleware('signed');
