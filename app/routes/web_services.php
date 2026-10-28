<?php

/*
|--------------------------------------------------------------------------
| Web Services Routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'ws', 'before' => 'ws-singleAuth'), function()
{

    //Route::get('/path', array( 'as'=>'', 'uses'=>''));


    Route::group(array('prefix' => 'vehicles'), function()
    {
		Route::get('last-positions', array('as'=>'vehicles-positions', 'uses'=>'VehiclesController@getVehiclesPositions'));

	    Route::group(array('prefix' => 'auth'), function()
	    {
		    Route::post('pair-device', array('as'=>'pair-device', 'uses'=>'AuthController@postPairDevice'));

		   	Route::post('unpair-device', array('as'=>'unpair-device', 'uses'=>'AuthController@postUnpairDevice'));
		    
		    //***** RUTAS QUE NECESITAN TOKEN *****//

		    Route::group(array('before' => 'ws-tokenAuth'), function()
		    {
		    });

	    });

	    Route::group(array('prefix' => 'tracking-route'), function()
	    {
		    Route::get('route-by-plate-date/{plate}/{date?}', array('as'=>'getRouteInfoByPlateAndDate', 'uses'=>'TrackingRoutesController@getRouteInfoByPlateAndDate'));

		    //***** RUTAS QUE NECESITAN TOKEN *****//

		    Route::group(array('before' => 'ws-tokenAuth'), function()
		    {
		    	Route::get('route-info', array('as'=>'ws-get-current-route', 'uses'=>'TrackingRoutesController@getCurrentRouteInfo'));

		    	Route::post('store-locations', array('as'=>'ws-store-locations', 'uses'=>'LocationHistoriesController@postStoreLocations'));

		    	Route::post('change-route-task-state', array('as'=>'ws-change-route-task-state', 'uses'=>'TrackingRoutesController@postChangeRouteTaskState'));

		    });

	    });

    });


});
/*
|--------------------------------------------------------------------------
| Web Services Monitor Routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'mo', 'before' => ''), function()
{

    //Route::get('/path', array( 'as'=>'', 'uses'=>''));

	Route::group(array('prefix' => 'auth'), function()
    {
		Route::post('login', array('as'=>'mo-login', 'uses'=>'AuthController@postLogin'));
		Route::get('logout', array('as'=>'mo-logout', 'uses'=>'AuthController@getLogout'));
		Route::get('auth-check', array('as'=>'mo-auth-check', 'uses'=>'AuthController@getAuthCheck'));
    });

    Route::group(array('prefix' => 'vehicles'), function()
    {
		Route::get('last-positions', array('as'=>'vehicles-positions', 'uses'=>'VehiclesController@getVehiclesPositions'));


	    Route::group(array('prefix' => 'tracking-route'), function()
	    {
		    Route::get('route-by-plate-date/{plate}/{date?}', array('as'=>'mo-route-info-by-plate-date', 'uses'=>'TrackingRoutesController@getRouteInfoByPlateAndDate'));
		    Route::post('create-route-comment', array('as'=>'mo-create-route-comment', 'uses'=>'TrackingRoutesController@postCreateRouteComment'));

	    });

    });


});