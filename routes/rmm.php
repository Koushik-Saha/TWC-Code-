<?php

// Rbt Media Manager Routes
Route::get('rbt-test', 'RMMController@storeDirectory');


Route::get('get-directories', 'RMMController@getDirectories');
Route::post('add-directory', 'RMMController@storeDirectory');
Route::patch('update-directory', 'RMMController@updateDirectory');
Route::delete('remove-directory/{directory}', 'RMMController@removeDirectory')->where('directory', '^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$');

Route::post('remove-image', 'RMMController@removeImage');
Route::post('download-image', 'RMMController@downloadImage');

Route::get('get-files/{directory?}', 'RMMController@getFiles')->where('directory', '^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$');
Route::post('upload/{directory?}', 'RMMController@storeImage')->where('directory', '^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$');


