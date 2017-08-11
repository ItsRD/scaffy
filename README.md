> This package is currently in development, any recommendations or feature requests are appreciated

# Scaffy - Scaffold your Laravel application
Never write a CRUD

## Requirements
- Laravel 5.4+
- PHP 7.1+


## Installation
Require this package with composer:

```
 composer require itsrd/scaffy 
 ```

After adding the package to composer you can add the service provider to the providers array in config/app.php

```php
ItsRD\Scaffy\ScaffyServiceProvider::class,
```

Now you've to publish the config, you can use the config to setup templates to create your very own scaffolder

```php
php artisan vendor:publish --provider="ItsRD\Scaffy\ScaffyServiceProvider"
```

Now you want to install the 'scaffy directory' to create your own templates

```php
php artisan scaffy:install
```

## Usage
To use scaffy, you can run this command:

```php
php artisan scaffy:scaffold {name} {--template}
```
- Name: Name of CRUD/Resource
- Template (optional)(default=default): You can create multiple templates in the config (see 'custom templates').

## Custom Templates (in progress)
Scaffy has already included a default template out of the box with some default settings. You can also set your own default template in the config file.

Templates are made to scaffold specific items on a website (e.g. admin). 

## Variables
You can use these variables in any stub file or filename, 
these variables will compile to the results listed below. 
You can also create params yourself in the scaffy config (templates > **template name** > params) 

In the results below we use the name 'page' 
```
php artisan scaffy:scaffold page
```

Variable | Explanation | Result 
------------ | ------------- | -------------
`&name&` | Entered name | page
`&class_name&` | Classname of entered 'name' | Page
`&snake_name&` | Snakecase of entered 'name' | page
`&controller_path&` | Default controller path (can be changed in config) | app/Http/Controllers
`&controller_ns&` | Default controller namespace | App\Http\Controllers
`&request_path&` | Default request path (can be changed in config) | app/Http/Requests
`&request_ns&` | Default request namespace | App\Http\Requests
`&model_path&` | Default model path (can be changed in config) | app/
`&model_ns&` | default model path | App
`&view_path` | laravel path to view | /resources/views
`&plural_name&` | Makes plural of 'name' | pages

## License
The MIT License (MIT). Please see [License File](https://github.com/ItsRD/scaffy/blob/master/LICENSE.md) for more information.