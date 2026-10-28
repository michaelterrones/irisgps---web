<?php

class AuthController extends \BaseController {

	public function postLogin()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		$response = UserService::login($email, $password);

		if(!JMUserAgent::isAndroidRequest()){
			if($response['status'] == 'error')
			{
				if($response['code'] == 'irs402')
				{
					return Redirect::back()->withError($response['data'])->withInput();
				}else{
					return Redirect::back()->withError('Hubo un error al procesar la acción.')->withInput();					
				}
			}else{
				/*
				if(!is_null($route))
				{
					return Redirect::route($route)->withSuccess('Vehículo creado satisfactoriamente');
				}
				*/
				return Redirect::route('getHome')->withSuccess('Se ha iniciado sesión correctamente');
			}
		}

		return $response;
	}

	public function getLogout()
	{

		$response = UserService::logout();

		if(!JMUserAgent::isAndroidRequest()){
			if($response['status'] == 'error')
			{
				if($response['code'] == 'irs402')
				{
					return Redirect::back()->withError($response['data'])->withInput();
				}else{
					return Redirect::back()->withError('Hubo un error al procesar la acción.')->withInput();					
				}
			}else{
				/*
				if(!is_null($route))
				{
					return Redirect::route($route)->withSuccess('Vehículo creado satisfactoriamente');
				}
				*/
				return Redirect::action('SiteController@getLogin')->withSuccess('Se ha cerrado sesión correctamente');
			}
		}

		return $response;
	}

	public function getAuthCheck()
	{

		$response = UserService::authCheck();

		return $response;
	}

	/*
	| Web Service
	*/

	public function postPairDevice()
	{
		$mobile =  Request::header('IMEI');

		$plate = Input::get('plate');

		$dni = Input::get('dni');

		return DeviceService::pairDevice($mobile, $plate, $dni);
	}

	public function postUnpairDevice()
	{
		$mobile =  Request::header('IMEI');

		return DeviceService::unpairDevice($mobile);
	}

	public function delete($class, $id)
	{
		$object = $class::findOrFail($id);
		try {
			$object->delete();
			$done = true;
		} catch (Exception $e) {
			$done = false;
		}

		if($done)
		{
			return Redirect::back()->withSuccess('Se ha eliminado el registro correctamente.');
		}else{
			return Redirect::back()->withError('No se pudo eliminado el registro. Es posible que este siendo referenciado.');
		}
	}

}