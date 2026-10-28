<?php

/*
|--------------------------------------------------------------------------
| Web app Routes
|--------------------------------------------------------------------------
*/

//Route::get('/path', array('as'=>'', 'uses'=>''));

//Route::get('/', array('as'=>'getHome', 'uses'=>'SiteController@getHome'));

Route::group(array('before' => 'guest'), function()
{	
	Route::get('login', array('as'=>'getLogin', 'uses'=>'SiteController@getLogin'));

	Route::post('login', array('as'=>'postLogin', 'uses'=>'AuthController@postLogin'));

	Route::post('recupear-password', array('as'=>'postRemind', 'uses'=>'RemindersController@postRemind'));

	Route::get('cambiar-password/{token?}', array('as'=>'getReset', 'uses'=>'RemindersController@getReset'));

	Route::post('cambiar-password', array('as'=>'postReset', 'uses'=>'RemindersController@postReset'));
});


Route::group(array('before' => 'auth'), function()
{
	Route::get('/', array('as'=>'getHome', 'uses'=>'VehiclesController@getVehiclesPositions'));

	Route::get('perfil', array('as'=>'getEditProfile', 'uses'=>'UsersController@getEditUser'));

	Route::post('perfil', array('as'=>'postEditProfile', 'uses'=>'UsersController@postEditUser'));

	Route::get('logout', array('as'=>'getLogout', 'uses'=>'AuthController@getLogout'));
	
	Route::get('eliminar/{class}/{id}', array('as'=>'delete', 'uses'=>'AuthController@delete'));

	Route::group(array('prefix' => 'vehiculos', 'before' => ''), function()
	{
		Route::get('posiciones', array('as'=>'getVehiclesPositions', 'uses'=>'VehiclesController@getVehiclesPositions'));

		Route::get('ruta-de-vehiculo/{id}', array('as'=>'getTrackingRouteById', 'uses'=>'TrackingRoutesController@getTrackingRouteById'));

		Route::get('recorrido-de-vehiculo/{plate}/{date?}', array('as'=>'getVehiclePathByDate', 'uses'=>'VehiclesController@getVehiclePathByDate'));

		Route::get('/', array('as'=>'getAllVehicles', 'uses'=>'VehiclesController@getAllVehicles'));

		Route::get('agregar', array('as'=>'getCreateVehicle', 'uses'=>'VehiclesController@getCreateVehicle'));
		
		Route::post('agregar/{route?}/{withInput?}', array('as'=>'postCreateVehicle', 'uses'=>'VehiclesController@postCreateVehicle'));

		Route::get('editar/{vehicle_id}', array('as'=>'getEditVehicle', 'uses'=>'VehiclesController@getEditVehicle'));

		Route::post('editar/{vehicle_id}', array('as'=>'postEditVehicle', 'uses'=>'VehiclesController@postEditVehicle'));


	});

	Route::group(array('prefix' => 'usuarios', 'before' => ''), function()
	{
		Route::get('/todo', array('as'=>'getAllUsers', 'uses'=>'UsersController@getAllUsers'));

		Route::get('agregar', array('as'=>'getCreateUser', 'uses'=>'UsersController@getCreateUser'));
		
		Route::post('agregar', array('as'=>'postCreateUser', 'uses'=>'UsersController@postCreateUser'));

		Route::get('editar/{id?}', array('as'=>'getEditUser', 'uses'=>'UsersController@getEditUser'));

		Route::post('editar/{id?}', array('as'=>'postEditUser', 'uses'=>'UsersController@postEditUser'));

	});

	Route::group(array('prefix' => 'dispositivos', 'before' => ''), function()
	{
		Route::get('/todo', array('as'=>'getAllDevices', 'uses'=>'DevicesController@getAllDevices'));

		Route::get('agregar', array('as'=>'getCreateDevice', 'uses'=>'DevicesController@getCreateDevice'));

		Route::post('agregar', array('as'=>'postCreateDevice', 'uses'=>'DevicesController@postCreateDevice'));

		Route::get('editar/{id}', array('as'=>'getEditDevice', 'uses'=>'DevicesController@getEditDevice'));

		Route::post('editar/{id}', array('as'=>'postEditDevice', 'uses'=>'DevicesController@postEditDevice'));
	});

	Route::group(array('prefix' => 'empleados', 'before' => ''), function()
	{
		Route::get('/', array('as'=>'getAllEmployees', 'uses'=>'EmployeesController@getAllEmployees'));

		Route::get('agregar', array('as'=>'getCreateEmployee', 'uses'=>'EmployeesController@getCreateEmployee'));
		
		Route::post('agregar', array('as'=>'postCreateEmployee', 'uses'=>'EmployeesController@postCreateEmployee'));

		Route::get('editar/{employee_id}', array('as'=>'getEditEmployee', 'uses'=>'EmployeesController@getEditEmployee'));

		Route::post('editar/{employee_id}', array('as'=>'postEditEmployee', 'uses'=>'EmployeesController@postEditEmployee'));
	});

	Route::group(array('prefix' => 'rutas', 'before' => ''), function()
	{
		Route::get('/', array('as'=>'getAllTrackingRoutes', 'uses'=>'TrackingRoutesController@getAllTrackingRoutes'));

		Route::get('agregar-ruta', array('as'=>'getCreateTrackingRoute', 'uses'=>'TrackingRoutesController@getCreateTrackingRoute'));

		Route::post('agregar-ruta', array('as'=>'postCreateTrackingRoute', 'uses'=>'TrackingRoutesController@postCreateTrackingRoute'));
		
		Route::get('confirmar-ruta/{tracking_route_id}', array('as'=>'getConfirmCreation', 'uses'=>'TrackingRoutesController@getConfirmCreation'));
		
		Route::post('agregar-comentario', array('as'=>'postCreateRouteComment', 'uses'=>'TrackingRoutesController@postCreateRouteComment'));

	});

});