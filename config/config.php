<?php

return [
    'default_disk'          => env('FILE_VIEWER_DISK', 'public'),
    'google_viewer_fallback' => env('FILE_VIEWER_GOOGLE_FALLBACK', false),

    'toolbar' => [
        'download'        => env('FILE_VIEWER_TOOLBAR_DOWNLOAD', true),
        'open_in_new_tab' => env('FILE_VIEWER_TOOLBAR_NEW_TAB', true),
        'copy_link'       => env('FILE_VIEWER_TOOLBAR_COPY_LINK', true),
        'fullscreen'      => env('FILE_VIEWER_TOOLBAR_FULLSCREEN', true),
    ],

    'csv_row_limit'    => env('FILE_VIEWER_CSV_ROW_LIMIT', 5000),
    'pptx_slides_scale' => env('FILE_VIEWER_PPTX_SLIDES_SCALE', '50%'),

    /*
     * Default Prism.js syntax-highlight theme for the code/text viewer.
     * Available values: prism, prism-dark, prism-okaidia, prism-tomorrow,
     * prism-twilight, prism-coy, prism-solarizedlight, prism-funky,
     * prism-atom-dark, prism-nord, prism-vsc-dark-plus, prism-dracula,
     * prism-one-dark, prism-material-dark
     */
    'code_theme' => env('FILE_VIEWER_CODE_THEME', 'prism-tomorrow'),
];
