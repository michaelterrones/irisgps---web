<?php

class VehiclesController extends \BaseController {

	public function getVehiclesPositions()
	{
		$response = VehicleService::vehiclesLastPositions();

		if(!JMUserAgent::isAndroidRequest()){
			$data = $response['data'];
			return View::make('site.vehicles.positions')->with(array('pageTitle'=>'Posicionamiento', 'data' => $data));
		}

		return $response;

	}

	public function getVehicleLastTrackingRoute($plate)
	{
		$response = VehicleService::vehicleLastPath($plate);
		$data = $response['data'];
		return View::make('site.vehicles.route')->with(array('pageTitle'=>'Posicionamiento', 'data' => $data));
	}

	public function getVehiclePathByDate($plate, $date = null)
	{
		$vehicle = Vehicle::where('plate', $plate)->first();
		$path = $vehicle->getLocationHistoryByDate($date);
		return View::make('site.vehicles.path')->with(array('pageTitle'=>'Posicionamiento', 'data' => array('vehicle' => $vehicle, 'path' => $path)));
	}

	public function getCreateVehicle()
	{

		$actionButton = array(
			'route'=>'getAllVehicles',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateVehicle', $routeParams = ['getAllVehicles', true], $method = 'POST', $legend = 'Crear nuevo vehículo', $submitName = 'Agregar', $resetName = null);
		$templator->addText($id = 'plate', $label = 'Placa', $name = 'plate', $placeholder = '?#?-###', $required = true, $autocomplete=false, $helpblock = "La placa debe de tener el formato '?#?-###' por ejemplo A1B-234");
		$templator->addSelectBasic($id = 'brand', $label = 'Marca', $name = 'brand', $elements = ['1' => 'Nissan', '2' => 'Toyota'], $haveEmptyOption = false);
		$templator->addSelectBasic($id = 'model', $label = 'Modelo', $name = 'model', $elements = ['Almera' => 'Almera'], $haveEmptyOption = false);
		$templator->addSelectBasic($id = 'color', $label = 'Color', $name = 'color', $elements = ['Rojo' => 'Rojo'], $haveEmptyOption = false);

		return View::make('site.vehicles.create')->with(array('pageTitle'=>'Agregar Vehículo', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postCreateVehicle($route = null, $withInput = false)
	{
		$input = Input::only('plate', 'brand', 'model', 'color');

		$response = VehicleService::createVehicle($input);

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
				if(!is_null($route))
				{
					return Redirect::route($route)->withSuccess('Vehículo creado satisfactoriamente');
				}
				return Redirect::back()->withSuccess('Vehículo creado satisfactoriamente');
			}
		}

		return $response;
	}


	public function getEditVehicle($vehicle_id)
	{
        $vehicle = Vehicle::findOrFail($vehicle_id);

		$actionButton = array(
			'route'=>'getAllVehicles',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postEditVehicle', $routeParams = [$vehicle->id], $method = 'POST', $legend = 'Editar vehículo', $submitName = 'Guardar', $resetName = null);
		$templator->addText($id = 'plate', $label = 'Placa', $name = 'plate', $placeholder = '?#?-###', $required = true, $autocomplete=false, $helpblock = "La placa debe de tener el formato '?#?-###' por ejemplo A1B-234", $value = $vehicle->plate);
		$templator->addElement(['type' => 'text', 'label' => 'Marca', 'name' => 'brand', 'id' => 'brand', 'value' => $vehicle->brand]);
		$templator->addElement(['type' => 'text', 'label' => 'Modelo', 'name' => 'model', 'id' => 'model', 'value' => $vehicle->model]);
		$templator->addElement(['type' => 'text', 'label' => 'Color', 'name' => 'color', 'id' => 'color', 'value' => $vehicle->color]);

		return View::make('site.CRUD.basic-form')->with(array('pageTitle'=>'Agregar Vehículo', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postEditVehicle($vehicle_id)
	{
        $vehicle = Vehicle::findOrFail($vehicle_id);

		$input = Input::only('plate', 'brand', 'model', 'color');

        $validator = Validator::make(
            $input,
            $vehicle->getUpdateRules()
        );

        if ($validator->fails())
        {
            $messages = $validator->errors()->toArray();
            return Redirect::back()->withValidationErrors($messages);
        }

        $vehicle->plate = $input['plate'];
        $vehicle->brand = $input['brand'];
        $vehicle->model = $input['model'];
        $vehicle->color = $input['color'];

        $vehicle->save();

		return Redirect::back()->withSuccess('Vehículo editado satisfactoriamente');
	}

	public function getAllVehicles()
	{
		$response = VehicleService::allVehicles();
		$data = $response['data'];

		$actionButton = array(
			'route'=>'getCreateVehicle',
			'title'=>'Agregar Nuevo',
			'class'=>'info'
		);

		return View::make('site.vehicles.all')->with(array('pageTitle'=>'Vehículos', 'actionButton'=>$actionButton, 'data' => $data));
	}

	/*
	public function index()
	{
		//
	}


	public function create()
	{
		//
	}


	public function store()
	{
		//
	}


	public function show($id)
	{
		//
	}


	public function edit($id)
	{
		//
	}


	public function update($id)
	{
		//
	}


	public function destroy($id)
	{
		//
	}
	*/

}