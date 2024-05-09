# A JSON Lines parser for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/luminarix/laravel-jsonl-parser.svg?style=flat-square)](https://packagist.org/packages/luminarix/laravel-jsonl-parser)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/luminarix/laravel-jsonl-parser/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/luminarix/laravel-jsonl-parser/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/luminarix/laravel-jsonl-parser/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/luminarix/laravel-jsonl-parser/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/luminarix/laravel-jsonl-parser.svg?style=flat-square)](https://packagist.org/packages/luminarix/laravel-jsonl-parser)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require luminarix/laravel-jsonl-parser
```

## Usage

```php
use Luminarix\JSONL\Facades\JSONL;

$filePath = "path/to/file.jsonl";

JSONL::parse(string $filePath): LazyCollection
JSONL::parseToDto(string $filePath, string $dtoClass): LazyCollection
JSONL::encode(array|Collection|LazyCollection $objects): string
JSONL::encodeFromDto(array|Collection|LazyCollection $dtos): string
JSONL::write(string $filePath, array|Collection|LazyCollection $objects, bool $lock = false): void
JSONL::writeFromDto(string $filePath, array|Collection|LazyCollection $dtos, bool $lock = false): void
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Luminarix Labs](https://github.com/luminarix)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
