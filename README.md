[![build status](https://github.com/vielhuber/comparehelper/actions/workflows/ci.yml/badge.svg)](https://github.com/vielhuber/comparehelper/actions)

# 📑 comparehelper 📑

comparehelper is a small php helper for comparing api responses in your tests.

## installation

install once with composer:

```
composer require vielhuber/comparehelper
```

then add this to your files:

```php
require __DIR__ . '/vendor/autoload.php';
use vielhuber\comparehelper\comparehelper;
```

## usage

```php
comparehelper::compare($var1, $var2); // true|false
```

you can use the following placeholders:

- `*`: any datatype
- `#STR#`: any string
- `#INT#`: any numeric value

## examples

```php
comparehelper::compare('foo', 'foo'); // true

comparehelper::compare(42, 42); // true

comparehelper::compare(
    [
        'foo' => 'bar'
    ],
    [
        'foo' => 'bar',
        'foo2' => 'bar'
    ]
); // false

comparehelper::compare(
    [
        'foo' => 'bar',
        'bar' => ['baz', 42]
    ],
    [
        '#STR' => '*',
        'bar' => ['#STR#', '#INT#']
    ]
); // true

// ordering is lazy
comparehelper::compare(['foo', 'bar'], ['bar', 'foo']); // true
comparehelper::compare(['#INT#', '#STR#'], [42, 'foo']); // true
comparehelper::compare(['#INT#', '#STR#'], ['foo', 42]); // false
comparehelper::compare(['foo' => 7, 'bar' => 42], ['bar' => 42, 'foo' => 7]); // true
comparehelper::compare(['#INT#' => 7, '#STR#' => 42], [7 => 7, 'foo' => 42]); // true
comparehelper::compare(['#INT#' => 7, '#STR#' => 42], ['foo' => 42, 7 => 7]); // false

// datatypes are strict
comparehelper::compare(42, '42'); // false
```

## phpunit integration

for better diff output in tests, use `assertEquals()` instead of `compare()`:

```php
// instead of:
$this->assertTrue(comparehelper::compare($expected, $actual));

// use this for better diffs:
comparehelper::assertEquals($expected, $actual);

// example:
comparehelper::assertEquals(['id' => '#INT#', 'name' => '#STR#', 'status' => 'active'], $apiResponse);
```
