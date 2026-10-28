<?php

/*
|--------------------------------------------------------------------------
| API Services Routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'api', 'before' => ''), function()
{

    //Route::get('/path', array( 'as'=>'', 'uses'=>''));

	Route::get('vehicles-last-positions', array('as'=>'vehicles-last-positions', 'uses'=>'VehicleService@vehiclesLastPositions'));


});