<?php

Route::post('fatoni/generate/api', array('as' => 'fatoni.generate.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@generateApi'));
Route::post('fatoni/update/api/{id}', array('as' => 'fatoni.update.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@updateApi'));
Route::get('fatoni/delete/api/{id}', array('as' => 'fatoni.delete.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@deleteApi'));

Route::resource('api/v1/resources', 'AhmadFatoni\ApiGenerator\Controllers\API\ResourcesController', ['except' => ['destroy', 'create', 'edit']]);
Route::get('api/v1/resources/{id}/delete', ['as' => 'api/v1/resources.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ResourcesController@destroy']);
Route::resource('api/v1/products', 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsController', ['except' => ['destroy', 'create', 'edit']]);
Route::get('api/v1/products/{id}/delete', ['as' => 'api/v1/products.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsController@destroy']);
Route::resource('api/v1/resourcetypes', 'AhmadFatoni\ApiGenerator\Controllers\API\ResourceTypesController', ['except' => ['destroy', 'create', 'edit']]);
Route::get('api/v1/resourcetypes/{id}/delete', ['as' => 'api/v1/resourcetypes.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ResourceTypesController@destroy']);