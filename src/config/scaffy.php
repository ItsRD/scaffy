<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Installation path
   |--------------------------------------------------------------------------
   |
   | This value is the path where scaffy will install it's folder
   */
    'scaffy_install'            => [
        app_path('Scaffy'),
    ],

    /*
   |--------------------------------------------------------------------------
   | Default template
   |--------------------------------------------------------------------------
   |
   | This is your default template
   */
    'template'      => 'default',

    /*
   |--------------------------------------------------------------------------
   | Templates
   |--------------------------------------------------------------------------
   |
   | You can use multiple templates with all different settings
   | e.g. when you have to scaffold any admin pages and something else
   */
    'templates' => [

        /*
       |--------------------------------------------------------------------------
       | Default template
       |--------------------------------------------------------------------------
       |
       | The default template is the one ready to rock and roll on your command
       | It has multiple settings to create your very own scaffolded pages
       */
        'default'       => [
            'view_path'         => resource_path('views/admin'),
            'controller_path'   => app_path('Http/Controllers'),
            'model_path'        => app_path('Models'),
            'request_path'      => app_path('Http/Requests'),
            'files'             => ['show'],
            'custom_files'      => [
                'create_request'        => ['requests/create.stub', '&request_path&/&class_name&/Create&class_name&Request.php'],
            ],
            /*
           |--------------------------------------------------------------------------
           | Params
           |--------------------------------------------------------------------------
           |
           | Params are custom variables which can be used in stub files and file name
           | by it's array key element
           | e.g. when the array key is 'view_prefix' it can be used as &view_prefix&
           */
            'params'    => [
                'view_prefix'           => 'admin',
                'route_prefix'          => 'admin',
            ],
        ],
    ],

];
