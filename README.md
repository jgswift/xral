xral
====

PHP 5.5+ resource abstraction layer

[![Build Status](https://travis-ci.org/jgswift/xral.png?branch=master)](https://travis-ci.org/jgswift/xral)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/xral/badges/quality-score.png?s=09ecf4d598dfdb7d99070e7ba8a7d197abddfae1)](https://scrutinizer-ci.com/g/jgswift/xral/)

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

## XML

### Query (SimpleXML)

```php
<?php
$query = new XML\Simple();

$query->select('//book')
      ->from('library.xml')
      ->where('authors/author','Stephen King');

$result = $query();

var_dump($result); // qinq\Collection [ SimpleXMLElement, SimpleXMLElement, ... ]
```

### Query (DOMDocument)

```php
<?php
$query = new XML\DOM();

$query->select('//book')
      ->from('library.xml')
      ->where('authors/author','Stephen King');

$result = $query();

var_dump($result); // qinq\Collection [ DOMElement, DOMElement, ... ]
```

## INI

### Query

```php
<?php
$query = new INI\Query();
            
$query->section('general')
      ->from('config.ini')
      ->where('debug',0);

$result = $query();

var_dump($result); // qinq\Collection [ array, array, ... ]
```

## YML

### Query

```php
<?php
$query = new YML\Query();

$query->select('product')
      ->from('invoice.yml')
      ->where('quantity',1);

$result = $query();

var_dump($result); // qinq\Collection [ array, array, ... ]
```

## JSON

### Query

```php
<?php
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
