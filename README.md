# CakeRestApi Plugin for CakePHP
[![Latest Stable Version](http://poser.pugx.org/ahmedebead/cake-rest-api/v)](https://packagist.org/packages/ahmedebead/cake-rest-api) [![Total Downloads](http://poser.pugx.org/ahmedebead/cake-rest-api/downloads)](https://packagist.org/packages/ahmedebead/cake-rest-api) [![Latest Unstable Version](http://poser.pugx.org/ahmedebead/cake-rest-api/v/unstable)](https://packagist.org/packages/ahmedebead/cake-rest-api) [![License](http://poser.pugx.org/ahmedebead/cake-rest-api/license)](https://packagist.org/packages/ahmedebead/cake-rest-api)

This plugin simplifies the CakeRestApi API development for your CakePHP 4.x application. It simply converts the output of your controller into a JSON response.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require ahmedebead/cake-rest-api "^0.1"
```

After installation, [Load the plugin](http://book.cakephp.org/4.0/en/plugins.html#loading-a-plugin)


Add this file to /src/Application.php
```php

// src/Application.php


 public function bootstrap(): void
    {
        $this->addPlugin('CakeRestApi', ['bootstrap' => true]);
        
        // Other code
    }

```

Or, you can load the plugin using the shell command

```sh
$ bin/cake plugin load CakeRestApi
```

## Usage

No major change requrires in the way you code in your CakePHP application. Simply, just add one parameter to your route configuration `isRest` like,

```php
$routes->connect('/foo/bar', ['controller' => 'Foo', 'action' => 'bar', 'isRest' => true]);
```

And extend your controller to `RestController` and everything will be handled by the plugin itself. For example,

```php
<?php

namespace App\Controller;

use CakeRestApi\Controller\RestController;

/**
 * Foo Controller
 *
 */
class FooController extends CakeRestApiController
{

    /**
     * bar method
     *
     * @return Response|void
     */
    public function bar()
    {
        $bar = [
            'falanu' => [
                'dhikanu',
                'tamburo'
            ]
        ];

        $this->set(compact('bar'));
    }
}

```

And that's it. You will see the response as below.

```json
{
    "status": "OK",
    "result": {
        "bar": {
            "falanu": [
                "dhikanu",
                "tamburo"
            ]
        }
    }
}
```

Doesn't it too simple? Whatever `viewVars` you set from your controller's action using `set()` method, will be converted into JSON response.

### Response Format
This plugin returns the response in the following format.

```json
{
    "status": "OK",
    "result": {
        ...
    }
}
```
The `status` key may contain OK or NOK based on your response code. For all successful responses, the code will be 200 and the value of this key will be OK. 

In case of error or exception, the value of `status` will become NOK. Also, based on your application's `debug` setting, it will contain the exception and trace data.

The `result` key contains the actual response. It holds all the variables set from your controller. This key will not be available in case of error/exception.

These properties are also available in your controller's `beforeFilter` method, so you can put additional authentication logic there.

## Reporting Issues
If you have a problem with this plugin or found any bug, please open an issue on [GitHub](https://github.com/ahmed3bead/cake-rest-api/issues).
