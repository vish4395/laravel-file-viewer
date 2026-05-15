<?php

use Illuminate\Support\Facades\Route;

/*
 * Signed so the ?url= parameter cannot be tampered with or injected
 * by a third party. The signature is verified before the view is served.
 */
Route::get('/laravel-file-viewer/docx-frame', function () {
    $url = request()->query('url');
    abort_if(empty($url), 400);
    return view('laravel-file-viewer::docxFrame', ['fileUrl' => $url]);
})->name('laravel-file-viewer.docx-frame')->middleware('signed');
