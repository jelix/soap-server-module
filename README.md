This is a module for Jelix, providing a Soap Response object and a SOAP coordinator,
to allow to implement SOAP service in your application built with the Jelix framework.
It can also generates automatically WSDL content.

It uses [the PHP SOAP API](http://www.php.net/manual/fr/book.soap.php)
so you have to configure your web server to include this extension.

This module is for Jelix 2.0.x and higher. See the jelix/jelix repository to see
its history before Jelix 2.0.

Setting up the module
=====================

Install it by hands like any other Jelix modules, or use Composer if you installed
Jelix 2.0+ with Composer.

In your project:

```
composer require "jelix/soap-server-module"
```

Launch the configurator for your application to enable the module

```bash
php dev.php module:configure jsoap
```

A new entrypoint is created and declared into your `app/system/framework.ini.php` and
`app/system/urls.xml`.

Then launch the installer to activate the module

```bash
php install/installer.php
```

Using the module
================

Controller
----------

Since it is a specific type of request, a controller filename must be suffixed
by ".soap.php". For example, for a "default" controller: ```default.soap.php```.
(it can co-exists with a “default” classic controller such as
“default.classic.php”).

### Method of a soap controller

The content of a controller is similar of a classical controller, with few
differences. You will retrieve a ```jResponseSoap``` object for the response,
which have the alias: "soap".

```php>
class defaultCtrl extends jController {

    /** 
     * Test with a simple parameter
     * @externalparam string $name
     * @return string
     */
    function hello() {
        $resp = $this->getResponse('soap');
        $resp->data = "Hello ".$this->param('name');
        return $resp;
    }
}
```

Each action of a controller will be in fact a "soap method".

### Declaring the type of parameters and the return value

A "soap method" have parameters and should receive a value. You should indicate
their type so Jelix could generate correctly soap and xsl messages.

To do it, just add "doc comments" (like for phpdoc), and indicates the type of
parameters and of the return value, by using some `@externalparam` tags and a `@return`
tag (we are using `@externalparam` instead of `@param` because these are not parameters
of the PHP method). Ex:

```
    @externalparam string $myparameter
```

Here it indicates that the soap parameter `$myparameter` is a string. Other
possible types are "integer", "int", "boolean", "float".

If you want to indicate an array, add the type name followed by ```[]```:

```
    @externalparam string[] $array_of_string
```

If it is an associative array, use ```[=>]```:

```
    @externalparam string[=>] $array_of_string
```


If you want to use complex type, like your own objects for parameters or return
values. The classes of this objects should be include in the file of the
controller or should be able to be autoloaded.


Here an exemple of an object use for a parameter ```MyTestStruct```:

```php
/**
 * Struct used for tests
 */
class MyTestStruct {
    /**
     * @var string
     */
    public $name = 'Dupont';

    /**
     * @var string
     */
    public $firstName = 'Bertrand';

    /**
     * @var string
     */
    public $city = 'Paris';
}
```

Note the use of the required "@var" keyword to indicate the type of each properties.

Then in your controller, don't forget to indicate ```MyTestStruct``` for
parameters or returned values:

```php
    /** 
     * for this method, we receive a MyTestStruct and return a MyTestStruct object
     * @externalparam MyTestStruct $input
     * @return MyTestStruct
     */
    function receiveObject() {
        $resp = $this->getResponse('soap');
        $input = $this->param('input');
        $input->name = 'Name updated';
        $resp->data = $input;
        return $resp;
    }
```

Of course, ```MyTestStruct``` can have some properties with complex type:

```php
/**
 * An other struct used for test, this one have an other object as member propertie
 */
class MyTestStructBis {

    /**
     * @var MyTestStruct
     */
    public $test;

    /**
     * @var string
     */
    public $msg = 'hello';

    function __construct(){
        $this->test = new MyTestStruct();
    }
}
```

Retrieve the URL of a service
-----------------------------

You retrieve an url for a soap action like this:

```php
  $url = jUrl::get("mymodule~myaction@soap");
```


Using the WSDL service
----------------------

When you use the SOAP protocol, you should provide some WSDL files, which allows
SOAP clients to know what are available SOAP methods.

The jsoap module provides features to generate WSDL. It contains a controller
named "WSDL" with a ```wsdl()``` method. So, just indicate the url of this
action to your soap client:

```
   http://mysite.com/index.php/jsoap/WSDL/wsdl?service=aModule~aController
```


Note that you should give a "service" parameter indicating the controller which
contains the web services. You can have more than one soap controller, but there
is no way to return automatically a WSDL file for all soap web services
implemented in your application.

You can display a HTML version of the list of SOAP services, by calling the
```index()``` method of the WSDL controller:

```
  http://mysite.com/index.php/jsoap/WSDL?service=aModule~aController
```

Unit tests
==========

Unit tests are in Testapp, in the jelix/jelix repository.
