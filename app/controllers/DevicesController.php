<?php

class DevicesController extends \BaseController {

	public function getAllDevices()
	{
		$response = DeviceService::allDevices();
		$data = $response['data'];

		$actionButton = array(
			'route'=>'getCreateDevice',
			'title'=>'Agregar Nuevo',
			'class'=>'info'
		);

		return View::make('site.devices.all')->with(array('pageTitle'=>'Dispositivos', 'actionButton'=>$actionButton, 'data' => $data));
	}

	public function getCreateDevice()
	{
		$actionButton = array(
			'route'=>'getAllDevices',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateDevice', $routeParams = null, $method = 'POST', $legend = 'Crear nuevo dispositivo', $submitName = 'Agregar', $resetName = null);
		$templator->addText($id = 'imei', $label = 'IMEI', $name = 'imei', $placeholder = '###', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addCheckboxSingle($id = 'activated', $name = 'activated', $value = 1, $label = "Activado", $checked = true);
		$templator->addCheckboxSingle($id = 'allowed', $name = 'allowed', $value = 1, $label = "Permitido", $checked = false);

		return View::make('site.CRUD.basic-form')->with(array('pageTitle'=>'Dispositivos', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postCreateDevice()
	{
		$input = Input::only('imei', 'activated', 'allowed');

		$response = DeviceService::createDevice($input);

		if(!JMUserAgent::isAndroidRequest()){
			if($response['status'] == 'error')
			{
				if($response['code'] == 'irs402')
				{
					return Redirect::back()->withValidationErrors($response['data'])->withInput();
				}else{
					return Redirect::back()->withValidationError('Hubo un error al procesar la acción.')->withInput();					
				}
			}else{
				/*
				if(!is_null($route))
				{
					return Redirect::route($route)->withSuccess('Vehículo creado satisfactoriamente');
				}
				*/
				return Redirect::route('getAllDevices')->withSuccess('Dispositivo creado satisfactoriamente');
			}
		}

		return $response;
	}

	public function getEditDevice($id)
	{
		$device = Device::findOrFail($id);

		$actionButton = array(
			'route'=>'getAllDevices',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postEditDevice', $routeParams = array($device->id), $method = 'POST', $legend = 'Editar dispositivo', $submitName = 'Guardar', $resetName = null);
		$templator->addText($id = 'imei', $label = 'IMEI', $name = 'imei', $placeholder = '###', $required = true, $autocomplete=false, $helpblock = null, $value = $device->mobile);
		$templator->addCheckboxSingle($id = 'activated', $name = 'activated', $value = 1, $label = "Activado", $checked = ($device->isActivated()) ? true : false);
		$templator->addCheckboxSingle($id = 'allowed', $name = 'allowed', $value = 1, $label = "Permitido", $checked = ($device->isAllowed()) ? true : false);

		return View::make('site.CRUD.basic-form')->with(array('pageTitle'=>'Dispositivos', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postEditDevice($id)
	{
		$device = Device::findOrFail($id);

		$input = Input::only('imei', 'activated', 'allowed');

		$validator = Validator::make(
		    $input,
		    $device->getUpdateRules()
		);

		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Redirect::back()->withValidationErrors($messages)->withInput();
		}

		$device->mobile = $input['imei'];
		$device->activated = is_null($input['activated']) ? 0 : 1;
		$device->allowed = is_null($input['allowed']) ? 0 : 1;

		if($device->save())
		{
			return Redirect::route('getAllDevices')->withSuccess('Registro actualizado correctamente')->withInput();
		}

		return Redirect::back()->withError('No se pudo actualizar el registro')->withInput();
	}
}