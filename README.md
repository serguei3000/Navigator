# ISPager

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A library to split results into multiple pages

## Install

Via Composer

``` bash
$ composer require serguei3000/navigator
```

## Usage


``` php
try {
  $pdo = new PDO(
    'mysql:host=localhost;dbname=test',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  $obj = new S_Pager\DBListener(
    new S_Pager\ListRange,
    $pdo,
    'table_name',
    1); //$way parameter defines the view of the Navigator
  echo "<pre>";
  print_r($obj->getItems());
  echo "</pre>";
  echo "<p>{$obj->SetNavigator()}</p>"; 
}
catch (PDOException $e) {
  echo "Can't connect to database";
}
```

## License

The MIT License (MIT). Please see [License File](https://github.com/dnoegel/php-xdg-base-dir/blob/master/LICENSE) for more information.