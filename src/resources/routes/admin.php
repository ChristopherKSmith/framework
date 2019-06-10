<?php

/*
|--------------------------------------------------------------------------
| Vanilo's Admin Routes
|
| Routes in this file will be added in a group attributes of which
| are to be defined in the box/module config file in the config
| key of routes.namespace, routes.prefix with smart defaults
|--------------------------------------------------------------------------
*/

//Note: Always define static routes first

//Product Upload Routes
Route::get('product/upload', 'ProductUploadController@index')->name('product_upload.index');
Route::post('product/upload', 'ProductUploadController@upload')->name('product_upload.upload');

Route::resource('taxonomy', 'TaxonomyController');
Route::resource('product', 'ProductController');
Route::resource('property', 'PropertyController');
Route::resource('order', 'OrderController');
Route::resource('media', 'MediaController')->only(['destroy', 'store']);

//Needed To add Properties to Product
Route::put('/properties/sync/{for}/{forId}', 'PropertyController@sync')->name('property.sync');

Route::get('/taxonomy/{taxonomy}/taxon/create', 'TaxonController@create')->name('taxon.create');
Route::post('/taxonomy/{taxonomy}/taxon', 'TaxonController@store')->name('taxon.store');
Route::get('/taxonomy/{taxonomy}/taxon/{taxon}/edit', 'TaxonController@edit')->name('taxon.edit');
Route::put('/taxonomy/{taxonomy}/taxon/{taxon}', 'TaxonController@update')->name('taxon.update');
Route::delete('/taxonomy/{taxonomy}/taxon/{taxon}', 'TaxonController@destroy')->name('taxon.destroy');

Route::put('/taxonomy/{taxonomy}/sync', 'TaxonomyController@sync')->name('taxonomy.sync');

Route::get('/property/{property}/value/create', 'PropertyValueController@create')->name('property_value.create');
Route::post('/property/{property}/value', 'PropertyValueController@store')->name('property_value.store');
Route::get('/property/{property}/value/{property_value}/edit', 'PropertyValueController@edit')->name('property_value.edit');
Route::put('/property/{property}/value/{property_value}', 'PropertyValueController@update')->name('property_value.update');
Route::delete('/property/{property}/value/{property_value}', 'PropertyValueController@destroy')->name('property_value.destroy');
Route::put('/property/sync/{for}/{forId}', 'PropertyValueController@sync')->name('property_value.sync');

Route::get('shipping/method', 'ShippingMethodController@index')->name('shipping_method.index');
Route::get('shipping/method/create', 'ShippingMethodController@create')->name('shipping_method.create');
Route::get('shipping/method/{shipping_method}/edit', 'ShippingMethodController@edit')->name('shipping_method.edit');
Route::post('shipping/method/store', 'ShippingMethodController@store')->name('shipping_method.store');
Route::put('shipping/method/{shipping_method}/update', 'ShippingMethodController@update')->name('shipping_method.update');

Route::get('product/{product}/sku/create', 'ProductSKUController@create')->name('product_sku.create');
Route::get('product/{product}/sku/{product_sku}/edit', 'ProductSKUController@edit')->name('product_sku.edit');
Route::post('product/{product}/sku/store', 'ProductSKUController@store')->name('product_sku.store');
Route::put('product/{product}/sku/{product_sku}', 'ProductSKUController@update')->name('product_sku.update');
Route::delete('product/{product}/sku/{product_sku}', 'ProductSKUController@destroy')->name('product_sku.destroy');