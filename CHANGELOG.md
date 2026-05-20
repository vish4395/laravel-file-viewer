
# Changelog

All notable changes to `laravel-file-viewer` will be documented in this file

## [1.0.0] - 2023-01-01

- Initial release

## [1.0.1] - 2023-01-03

### Changed
- Updated `composer.json` to require `laravel/framework` version `^8.0|^9.0|^10.0` for broader compatibility.

## [1.0.2] - 2023-03-10

### Added
- Added new asset: `resources/assets/officetohtml/jquery/jquery.min.js` for improved jQuery support.

### Changed
- Improved README.md formatting and documentation (added star history, removed sponsors).
- Minor publishing logic change in `LaravelFileViewerServiceProvider.php` for asset publishing.

## [1.0.3] - 2025-05-22

### Added
- New Blade view: `previewFileDocxjs.blade.php` for DOCX preview using docx-preview.js.

### Changed
- All preview Blade views updated to use camelCase variables (`fileName`, `fileUrl`, `fileData`, `iconClass`).
- Icon logic updated to use Font Awesome 6 and `fa-solid` classes.
- Layout now uses Font Awesome 6 Free CDN.
- Enhanced file type detection and preview logic in `LaravelFileViewer.php`:
	- DOCX files use the new docxjs preview.
	- Improved handling for application/* MIME types.
	- Refactored method signatures to use camelCase and static methods.
- Various bug fixes and code cleanups in Blade views and main class.

## [1.1.0] - 2026-05-19

### Added
- Dedicated viewer routes for PDF, Excel (XLSX), PowerPoint (PPTX), and CSV with their own Blade views.
- Luckysheet-based Excel viewer with chart and formula support (replaces plain HTML table renderer).
- `default_disk` config option to select the filesystem disk used for resolving files.
- `google_viewer_fallback` config option to opt into the Google Docs viewer for unsupported types.
- Per-button toolbar visibility config to show/hide individual toolbar actions.
- Tailwind CSS–based viewer redesign: new header, full-viewport layout, and consistent loading states.
- Audio visualizer on the audio preview; loading indicator on the image preview.

### Changed
- Upgraded Bootstrap 4 → 5 across all Blade views, including BS5 class renames.
- Upgraded Video.js 7 → 8 and Viewer.js 1.11.1 → 1.11.6; trimmed unused Video.js themes.
- Office fallback view slimmed down now that PDF/XLSX/PPTX/CSV route to dedicated views.
- README rewritten for the current API, config options, and supported file types.

### Fixed
- DOCX preview now renders inside an iframe for full CSS isolation from Bootstrap and host styles; restored proper Word formatting and theme rendering.
- Excel viewer no longer crashes with SIGILL — replaced SheetJS 0.10.2 (902 KB) with 0.20.3 mini (273 KB).
- Removed Handsontable Pro dependency that was license-blocked in production.
- Video preview restored to Video.js + forest theme with proper responsive layout (removed Bootstrap ratio wrapper that conflicted with the Video.js wrapper).

### Security
- Corrected SRI hashes for Bootstrap JS and Viewer.js assets.
- Code-review fixes: tightened security checks, removed dead code, upgraded outdated APIs, refreshed SRI hashes.

## [1.0.4] - 2025-09-10

### Refactored Core Logic
- Removed duplicate file existence checks in `LaravelFileViewer.php`.
- Unified error handling for missing files.
- Fixed unreachable code and redundant returns in icon class logic.

### UI/UX Improvements
- Enhanced image preview with a loading indicator and better viewer initialization.
- Added an audio visualizer to the audio preview for a richer user experience.

### Assets
- Added a new loading GIF for improved feedback during image loading.

### Documentation
- Updated README with improved formatting, new star history chart, and removed sponsor section.
- Minor corrections and enhancements for clarity.
