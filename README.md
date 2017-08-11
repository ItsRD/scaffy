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

## Custom Templates

## License
