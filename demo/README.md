# Demo Files

Minimal but valid sample files for every supported file type.
Use these to test the viewer locally or in CI.

| File | Type | Viewer |
|------|------|--------|
| `sample.txt` | Plain text | Prism.js syntax highlight |
| `sample.csv` | CSV | Inline sortable table |
| `sample.json` | JSON | Prism.js syntax highlight |
| `sample.png` | PNG image | Viewer.js |
| `sample.jpg` | JPEG image | Viewer.js |
| `sample.pdf` | PDF | PDF.js |
| `sample.docx` | Word document | docx-preview.js |
| `sample.xlsx` | Excel spreadsheet | Luckysheet |
| `sample.pptx` | PowerPoint | PPTXjs |
| `sample.odt` | ODF text | WebODF |
| `sample.ods` | ODF spreadsheet | WebODF |
| `sample.odp` | ODF presentation | WebODF |
| `sample.wav` | WAV audio | HTML5 `<audio>` |
| `sample.zip` | ZIP archive | Details panel |

## Usage

Copy files to `storage/app/public/demo/` and preview via your controller:

```php
return LaravelFileViewer::show(
    fileName: 'sample.pdf',
    filePath: 'demo/sample.pdf',
    fileUrl:  asset('storage/demo/sample.pdf'),
    disk:     'public',
);
```
