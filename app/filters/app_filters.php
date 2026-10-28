<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/


Route::filter('ws-singleAuth', function()
{
	if (!is_null(Request::header('IMEI')) && !is_null(Request::header('gcm')))
	{
		$device = Device::findByMobile(Request::header('IMEI'));

		if(is_null($device))
		{
			return Response::noAuth(': el dispositivo no está registrado');
		}

		if($device->isActivated())
		{
			if(!$device->isAllowed())
			{
				return Response::noAuth(': el dispositivo no está permitido');
			}

			Device::registerGCM(Request::header('IMEI'));

			//->ejecuta la acción correspondiente

		}else{
			return Response::noAuth(': el dispositivo no está activado');
		}
	}
	else
	{
		return Response::invalid(false, true, ': el número de teléfono o id gcm no han sido encontrado en la solicitud');
	}
});

Route::filter('ws-tokenAuth', function()
{
	if(!is_null(Request::header('token')))
	{
		if (!Tokenizer::validate(Request::header('token'), Request::header('IMEI')))
		{
			return Response::noAuth(': no hay un token válido');
		}
	}else
	{
		return Response::invalid(false, true, ': no se ha encontrado un token en la solicitud');
	}
});

///////////////////////////////////

Route::filter('site', function()
{
	if (Input::has('token') && Input::has('mobile'))
	{
		return Response::noAuth();
	}
});

Route::filter('site', function()
{
	if (Input::has('token') && Input::has('mobile'))
	{
		return Response::noAuth();
	}
});