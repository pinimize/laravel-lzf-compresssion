# Pinimize LZF Extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pinimize/laravel-lzf-compresssion.svg?style=flat-square)](https://packagist.org/packages/pinimize/laravel-lzf-compresssion)
[![Total Downloads](https://img.shields.io/packagist/dt/pinimize/laravel-lzf-compresssion.svg?style=flat-square)](https://packagist.org/packages/pinimize/laravel-lzf-compresssion)
[![Tests](https://github.com/pinimize/laravel-lzf-compresssion/actions/workflows/phpunit.yml/badge.svg?branch=main)](https://github.com/pinimize/laravel-lzf-compresssion/actions/workflows/phpunit.yml)
[![License](https://img.shields.io/packagist/l/pinimize/laravel-lzf-compresssion.svg?style=flat-square)](https://packagist.org/packages/pinimize/laravel-lzf-compresssion)

This package provides LZF compression support for the Pinimize compression and archive package for Laravel.

## Requirements

- PHP 8.2 or higher
- Laravel 11 or higher
- The `ext-lzf` PHP extension must be installed and enabled

## Installation

First, ensure that the LZF PHP extension is installed. You can check this by running:

```bash
php -m | grep lzf
```

If you don't see 'lzf' in the output, you'll need to install the extension before proceeding.

Once the LZF extension is installed, you can install the package via composer:

```bash
composer require pinimize/laravel-lzf-compresssion
```

## Prerequisites

This package is an extension of the [Pinimize Compression and Archive](https://github.com/pinimize/laravel-compression-and-archive) package. The main Pinimize package will be automatically installed as a dependency for this package.

## Configuration

After installation, you need to add the LZF driver configuration to your `config/pinimize.php` file. If you haven't published th main config file, you can do so by running this command:

```bash
php artisan vendor:publish --provider="Pinimize\PinimizeServiceProvider" --tag="config"
````

Add the following to the `drivers` array:
```php
'lzf' => [
    'disk' => env('COMPRESSION_DISK', null),
],
```

Your complete compression configuration should look something like this:

```php
'compression' => [
    'default' => env('COMPRESSION_DRIVER', 'gzip'),
    'mixin' => env('COMPRESSION_REGISTER_MIXIN', true),
    'drivers' => [
        ... // Other drivers
        'lzf' => [
            'disk' => env('COMPRESSION_DISK', null),
        ],
    ],
],
```

## Usage

Once configured, you can use the LZF driver just like any other compression driver in Pinimize. All methods available in the main Pinimize package are fully supported with the LZF driver. For example:

```php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Pinimize\Facades\Compression;
use Pinimize\Facades\Decompression;

// When you have made lzf the default driver in your configuration:
$compressedStringZlib = Str::compress($originalString);
$decompressedStringGzip = Str::decompress($compressedStringGzip);

// By specifying the driver:
$compressedStringZlib = Str::compress($originalString, 'lzf');
$decompressedStringGzip = Str::decompress($compressedStringGzip, 'lzf');

// Or with the Storage facade:
Storage::compress('/path/to/original.json', '/path/to/compressed.json.lzf');
Storage::decompress('/path/to/compressed.json.lzf', '/path/to/decompressed.json');

// Compress a string using LZF
$compressed = Compression::driver('lzf')->string('Hello, World!');

// Default driver
$compressed = Compression::string('Hello, World!');
Compression::put('path/to/compressed.csv.lzf', '/path/to/original.csv');
```

For more detailed usage instructions and a full list of available methods, please refer to the [Pinimize Compression and Archive documentation](https://github.com/pinimize/laravel-compression-and-archive).

## Important Notes

1. The LZF compression algorithm does not support compression levels. The `level` parameter in the configuration is ignored for the LZF driver.
2. The LZF extension must be installed on your server for this package to work. If you're using a managed hosting service, you may need to contact your hosting provider to have it installed.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Testing

Run the tests with:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Einar Hansen](https://github.com/einar-hansen)
- [All Contributors](https://github.com/pinimize/laravel-compression-and-archive/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.