
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
