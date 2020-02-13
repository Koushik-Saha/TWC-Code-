<?php

/*
* Configuration for Media manager
*/

return [
    /*
     * Middleware for Media manager
     */
    'middleware'        => ['web'],

    /*
     * Default storage path(full) for Media manager
     */
    'storage_path'      => storage_path(),

    /*
     * Default upload folder for Media manager
     */
    'storage_folder'    => 'uploads',

    /*
     * Default route for showing image as http image response
     */
    'image_cache_route' => '/uploads/public/cache',
    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submited
    | by URI.
    |
    | Define as many directories as you like.
    | Must Match: storage_path/storage_folder
    */

    'image_cache_paths' => [
        storage_path('uploads')
    ],

    'image_cache_templates' => [
        'small'     => 'Intervention\Image\Templates\Small',
        'medium'    => 'Intervention\Image\Templates\Medium',
        'large'     => 'Intervention\Image\Templates\Large',
        '80'        => 'App\Http\Controllers\RbtMediaManager\Rmm\ImageCacheFilter80',
        '150'       => 'App\Http\Controllers\RbtMediaManager\Rmm\ImageCacheFilter150',
        '200'       => 'App\Http\Controllers\RbtMediaManager\Rmm\ImageCacheFilter200',
        '400'       => 'App\Http\Controllers\RbtMediaManager\Rmm\ImageCacheFilter400'
    ]

];
