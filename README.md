# BtSlowLog - ZF2 Module that logs slow response times

Version 1.0 Created by [Benjamin Tigano](http://benjamin-t.com/)

## Introduction

This ZF2 module is used for logging slow response times to a Zend\Log instance.

## Installation

To install BtSlowLog, recursively clone this repository (`git clone
--recursive`) into your ZF2 modules directory or download and extract into
your ZF2 modules directory.

## Enable the module

Once you've installed the module, you need to enable it. You can do this by 
adding it to your `config/application.config.php` file:

```php
<?php
return array(
    'modules' => array(
        'Application',
        'BtSlowLog',
    ),
);
```

## Configuration

Create a file in `config/autoload` named `logs.local.php` with the following contents:
```php
return array(
    'log' => array(
        'MySlowLogger' => array(
            'writers' => array(
                array(
                    'name' => 'Zend\Log\Writer\Stream',
                    'options' => array(
                        "stream" => "data/slow.log"
                    )
                )
            )
        ),
    ),
);
```

Data will be logged to the `data/slow.log` file.

To adjust the threshold, edit the `threshold` value in `BtSlowLog/config/module.config.php`.

## License

BtSlowLog is released under a New BSD license. See the included LICENSE file.
