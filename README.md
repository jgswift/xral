xral
====

PHP 5.5+ resource abstraction layer

[![Build Status](https://travis-ci.org/jgswift/xral.png?branch=master)](https://travis-ci.org/jgswift/xral)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/xral/badges/quality-score.png?s=09ecf4d598dfdb7d99070e7ba8a7d197abddfae1)](https://scrutinizer-ci.com/g/jgswift/xral/)
[![Latest Stable Version](https://poser.pugx.org/jgswift/xral/v/stable.svg)](https://packagist.org/packages/jgswift/xral)
[![License](https://poser.pugx.org/jgswift/xral/license.svg)](https://packagist.org/packages/jgswift/xral)
[![Coverage Status](https://coveralls.io/repos/jgswift/xral/badge.png?branch=master)](https://coveralls.io/r/jgswift/xral?branch=master)

## Installation

Install via cli using [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/xral:0.1.*
```

Install via composer.json using [composer](https://getcomposer.org/):
```json
{
    "require": {
        "jgswift/xral": "0.1.*"
    }
}
```

## Description

xral is a relatively simple package that provides a consistent means to query data using XML, YML, and INI file formats.
xral includes 3 query classes to read from and write to each resource respectively.
xral also includes 3 adapter and view classes to provide a base for more specific implementations.
xral relies on ```kfiltr``` hooks to customize filtering and mapping procedures performed on query results.
xral queries return ```qinq``` collections to easily enable further object-level transformations 

## Dependency

* php 5.5+
* [jgswift/qtil](http://github.com/jgswift/qtil) - general utilities
* [jgswift/qio](http://github.com/jgswift/qio) - i/o utilities
* [jgswift/observr](http://github.com/jgswift/observr) - observer pattern implementation
* [jgswift/kenum](http://github.com/jgswift/kenum) - enumerator implementation
* [jgswift/qinq](http://github.com/jgswift/qinq) - quasi integrated queries
* [jgswift/kfiltr](http://github.com/jgswift/kfiltr) - filter, map, and hook implementations
* [symfony/yaml](http://github.com/symfony/yaml) - enumerator implementation

## Usage

### XML

#### SimpleXML

#### Basic

```php
$query = new XML\Simple();

$query->select('//book')
      ->from('library.xml')
      ->where('authors/author','Stephen King');

$result = $query();

var_dump($result); // qinq\Collection [ SimpleXMLElement, SimpleXMLElement, ... ]
```

#### Update

```php
$file = new qio\File('library.xml');

$query = new XML\Simple();

// set the author of a specific book
$query->select('library/books/book')
      ->update($file)
      ->set('author','Stephen King')
      ->where('name','The Langoliers');

$query();
```

#### Insert

```php
$file = new qio\File('library.xml');

$query = new XML\Simple();
            
$query->select('/library/books')
      ->update($file)
      ->insert(['book' => [
          'name' => 'The Catcher In The Rye',
          'authors' => [
              'author' => 'J. D. Salinger'
          ],
          'ISBN' => '0316769533 9780316769532',
          'publisher' => 'Amazon',
          'pages' => 277
      ]]);

$query();
```

#### Delete

```php
$file = new qio\File('library.xml');

$query = new XML\Simple();

$query->delete('//book')
      ->update($file)
      ->where('name','The Catcher In The Rye');

$query();
```

#### DOMDocument

#### Basic

```php
$query = new XML\DOM();

$query->select('//book')
      ->from('library.xml')
      ->where('authors/author','Stephen King');

$result = $query();

var_dump($result); // qinq\Collection [ DOMElement, DOMElement, ... ]
```

#### Update

```php
$file = new qio\File('library.xml');

$query = new XML\DOM();

$query->select('library/books/book')
      ->update($file)
      ->set('author','Stephen King')
      ->where('name','The Langoliers');

$query();
```

#### Insert

```php
$file = new qio\File('library.xml');

$query = new XML\DOM();
            
$query->select('/library/books')
      ->update($file)
      ->insert(['book' => [
          'name' => 'The Catcher In The Rye',
          'authors' => [
              'author' => 'J. D. Salinger'
          ],
          'ISBN' => '0316769533 9780316769532',
          'publisher' => 'Amazon',
          'pages' => 277
      ]]);

$query();
```

#### Delete

```php
$file = new qio\File('library.xml');

$query->delete('//book')
      ->update($file)
      ->where('name','The Catcher In The Rye');

$query();
```

### INI

#### Basic

```php
$query = new INI\Query();
            
$query->section('general')
      ->from('config.ini')
      ->where('debug',0);

$result = $query();

var_dump($result); // qinq\Collection [ array, array, ... ]
```

#### Update

```php
$file = new qio\File('config.ini');

$query = new INI\Query();

// update debug setting in general section to 1
$query->update($file)
      ->section('general')
      ->set('debug',1);

$query();
```

#### Insert

```php
$file = new qio\File('config.ini');

$query->update($file)
      ->section('general')
      ->insert('name','My Application');

$query();
```

#### Delete

```php
$file = new qio\File('config.ini');

$query = new INI\Query();
            
$query->update($file)
      ->section('general')
      ->delete('name');

$query();
```

### YML

#### Basic

```php
$query = new YML\Query();

$query->select('product')
      ->from('invoice.yml')
      ->where('quantity',1);

$result = $query();

var_dump($result); // qinq\Collection [ array, array, ... ]
```

#### Update

```php
$query = new YML\Query();

$query->select('product')
      ->update('invoice.yml')
      ->where('sku','BB5280R')
      ->set('quantity',5);

$query();
```

#### Insert

```php
$query = new YML\Query();
            
$query->select('product')
      ->update('invoice.yml')
      ->insert([
          'sku' => 'BB5280R',
          'quantity' => 6,
          'description' => 'Baseball Glove',
          'price' => 50
      ]);

$query();
```

#### Delete

```php
$query = new YML\Query();

$query->delete('product')
      ->update('invoice.yml')
      ->where('sku','BB5280R');

$query();
```

### JSON

#### Query

```php
$query = new JSON\Query();

$query->select(function($person) {
            return $person['firstName'].' '.$person['lastName']
      })
      ->from('people.json')
      ->where(function($person) {
            return ($person['money'] > 5000) ? true : false;
      });

$result = $query();

var_dump($result); // qinq\Collection [ [ 'name' => 'john doe' ], [ 'name' => 'billy bob' ] ]
```

#### Update

```php
$file = new qio\File('people.json');

$query = new JSON\Query();

// change a persons firstname from 'billy' to 'bob'
$query->update($file)
      ->set('firstName','bob')
      ->where(function($person) {
          return ($person['firstName'] == 'billy') ? true : false;
      });

$query();
```

#### Insert

```php
$file = new qio\File('people.json');

$query = new JSON\Query();
            
$query->update($file)
      ->insert([
          'firstName' => 'jane',
          'lastName' => 'doe',
          'gender' => 'female',
          'money' => 50000
      ]);

$query();
```

#### Delete

```php
$file = new qio\File('people.json');

$query = new JSON\Query();
            
$query->update($file)
      ->delete()
      ->where('gender','female');

$query();
```
