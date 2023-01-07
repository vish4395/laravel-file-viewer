# Laravel File Viewer - A package to see preview of diffrent file types

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vish4395/laravel-file-viewer.svg?style=flat-square)](https://packagist.org/packages/vish4395/laravel-file-viewer)
[![Total Downloads](https://img.shields.io/packagist/dt/vish4395/laravel-file-viewer.svg?style=flat-square)](https://packagist.org/packages/vish4395/laravel-file-viewer)
![GitHub](https://img.shields.io/github/license/vish4395/laravel-file-viewer?style=flat-square)
[![Twitter URL](https://img.shields.io/twitter/url?color=blue&logo=twitter&style=flat-square&url=https%3A%2F%2Fgithub.com%2Fvish4395%2Flaravel-file-viewer%2F)](https://twitter.com/intent/tweet?text=Checkout%20this%20awesome%20package%0Ahttps%3A//github.com/vish4395/laravel-file-viewer/)

Laravel File Viewer is a wrapper for implementing different JS libraries to view files according to their types. It supports images, videos, audio, docx, pptx, xlsx and pdfs etc. 

## Installation

You can install the package via composer:

```bash
composer require vish4395/laravel-file-viewer
```

Publish assets 
```bash
php artisan vendor:publish  --provider="Vish4395\LaravelFileViewer\LaravelFileViewerServiceProvider" --tag=assets
```

Publish views (optional)(for customize ui) 
```bash
php artisan vendor:publish  --provider="Vish4395\LaravelFileViewer\LaravelFileViewerServiceProvider" --tag=views
```

## Usage

Add alias

```php
    'aliases' => Facade::defaultAliases()->merge([
        'LaravelFileViewer' => Vish4395\LaravelFileViewer\LaravelFileViewerFacade::class,
    ])->toArray(),
```

Example 
```php
use LaravelFileViewer;
/*
 * ...
 */
public function file_preview($filename){
        $filepath='public/'.$filename;
        $file_url=asset('storage/'.$filename);
        $file_data=[
          [
            'label' => __('Label'),
            'value' => "Value"
          ]
        ];
        return LaravelFileViewer::show($filename,$filepath,$file_url,$file_data);
      }
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
You are most welcome to contribute this project
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
please email vishal@newai.in for contribute this project or create PR.

### Security
If you discover any security related issues, please email vishal@newai.in instead of using the issue tracker.

## Credits

-   [Vishal Sharma](https://github.com/vish4395)
-   [meshesha/officetohtml](https://github.com/meshesha/officetohtml)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.



## Demo:

https://user-images.githubusercontent.com/12929023/210215225-000507cf-d8f4-4e5b-b7ad-ad6a2276ac93.mp4

