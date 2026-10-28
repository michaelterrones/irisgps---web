<?php

class TrackingRoutesController extends \BaseController {

	public function getAllTrackingRoutes()
	{
		$response = TrackingRouteService::allTrackingRoutes();
		$data = $response['data'];

		$actionButton = array(
			'route'=>'getCreateTrackingRoute',
			'title'=>'Agregar Nuevo',
			'class'=>'info'
		);

		return View::make('site.tracking-routes.all')->with(array('pageTitle'=>'Rutas', 'actionButton'=>$actionButton, 'data' => $data));
	}

	public function getCreateTrackingRoute()
	{

		$actionButton = array(
			'route'=>'getAllVehicles',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateTrackingRoute', $routeParams = null, $method = 'POST', $legend = 'Crear nueva ruta', $submitName = 'Agregar', $resetName = 'Reset');
		$templator->addText($id = 'date', $label = 'Fecha', $name = 'date', $placeholder = 'dd/mm/YYYY', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addSelectBasic($id = 'vehicle_id', $label = 'Vehículo', $name = 'vehicle_id', $elements = Vehicle::all()->lists('plate', 'id'), $haveEmptyOption = false);
		$templator->addDualListBox($id = 'customers', $label = 'Clientes', $name = 'customers', $elements = Customer::all()->lists('name', 'id'), $rows = 10, $fromName = 'Todos los clientes', $toName='Elegidos para visitar', $size = 7);
		$templator->addLinkButton('postCreateVehicle', $params = null, $class = 'info', $name = 'Agregar Cliente <i class="fa fa-plus"></i>');
		$templator->addDualListBox($id = 'passengers', $label = 'Pasajeros', $name = 'passengers', $elements = Employee::all()->lists('first_name', 'id'), $rows = 10, $fromName = 'Empleados', $toName='Elegidos para esta ruta', $size = 7);
		$templator->addLinkButton('postCreateVehicle', $params = null, $class = 'info', $name = 'Agregar Empleado <i class="fa fa-plus"></i>');

		return View::make('site.tracking-routes.create')->with(array('pageTitle'=>'Rutas', 'actionButton'=>$actionButton, 'templator' => $templator));
	}	

	public function postCreateTrackingRoute()
	{
		$date = Input::get('date');
		$vehicle_id = Input::get('vehicle_id');
		$customers = Input::get('customers');
		$passengers = Input::get('passengers');

		$customers = array_unique(is_null($customers) ? array() : $customers);
		$passengers = array_unique(is_null($passengers) ? array() : $passengers);

		$input = array(
			'date' => $date,
			'vehicle_id' => $vehicle_id,
			'customers' => $customers,
			'passengers' => $passengers
		);

		$validator = Validator::make(
		    $input,
		    TrackingRoute::$rules
		);
		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			//return Response::invalid(false, false, ": los datos tienen errores", $messages);
			return Redirect::back()->withValidationErrors($messages)->withInput();
		}

		$tracking_route = new TrackingRoute;
		$tracking_route->date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d H:i:s');
		$tracking_route->vehicle_id = $vehicle_id;
		$tracking_route->save();

		foreach ($customers as $customer_id) {
			$route_task = new RouteTask;
			$route_task->customer_id = $customer_id;
			$route_task->task_state_id	= 1;
			$route_task->tracking_route_id = $tracking_route->id;
			$route_task->description = 'Programado por el Sistema';
			$route_task->save();

			$task_state_history = new TaskStateHistory;
			$task_state_history->route_task_id = $route_task->id;
			$task_state_history->task_state_id = $route_task->task_state_id;
			$task_state_history->description = 'Programado por el Sistema';
			$task_state_history->save();
		}

		$employee_counter = 0;

		foreach ($passengers as $employee_id) {
			$passenger = new Passenger;
			$passenger->employee_id = $employee_id;
			$passenger->tracking_route_id = $tracking_route->id;
			$passenger->passenger_type_id = ($employee_counter == 0) ? 1 : 2;
			$passenger->save();
			$employee_counter++;
		}

		return Redirect::route('getCreateTrackingRoute')->withSuccess('Ruta creada correctamente.');
	}

	public static function getConfirmCreation($tracking_route_id)
	{
		$tracking_route = TrackingRoute::find($tracking_route_id);

		$elements = $tracking_route->employees()
				->select(DB::raw('concat(first_name, " ", last_name) as fullname, passengers.id'))
				->lists('fullname', 'id');

		$driver_id = $tracking_route->getDriverId();

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateTrackingRoute', $routeParams = null, $method = 'POST', $legend = 'Confirmar información de ruta', $submitName = 'Agregar', $resetName = 'Reset');
		$templator->addText($id = 'date', $label = 'Fecha', $name = 'date', $placeholder = 'dd/mm/YYYY', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addRadioMultiple($id = 'casas', $name = 'casa', $label = 'Conductor', $elements, $checked = $driver_id);

		return View::make('site.tracking-routes.confirm-creation')->with(array('pageTitle'=>'Rutas', 'actionButton'=>null, 'templator' => $templator));
	}

	public static function getCurrentRouteInfo($plate = null)
	{
		if(!$plate)
		{
			$token = Request::header('token');

			$plate = DeviceService::getPairedVehiclePlateByToken($token);
		}

		return TrackingRouteService::currentRouteInfo($plate);
	}

	public static function getRouteInfoByPlateAndDate($plate, $date = null)
	{
		if(is_null($date))
		{
			$response = TrackingRouteService::currentRouteInfo($plate);
		}else
		{
			$response = TrackingRouteService::getRouteInfoByPlateAndDate($plate, $date);
		}

		return $response;
	}

	public static function postCreateRouteComment()
	{
		$tracking_route_id = Input::get('tracking_route_id');
		$comment = Input::get('comment');

		$response = TrackingRouteService::registerComment($tracking_route_id, $comment);

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
					return Redirect::route($route)->withSuccess('Comentario creado satisfactoriamente');
				}*/
				return Redirect::back()->withSuccess('Comentario creado satisfactoriamente');
			}
		}

		return $response;
	}	

	public static function postChangeRouteTaskState()
	{
		$route_task_id = Input::get('route_task_id');
		$description = Input::get('description');
		$task_state_id = Input::get('task_state_id');

		$response = TrackingRouteService::changeRouteTaskState($route_task_id, $task_state_id, $description);

		return $response;
	}

    public static function getTrackingRouteById($tracking_route_id)
    {
        $response = TrackingRouteService::getTrackingRouteById($tracking_route_id);
        $data = $response['data'];
        return View::make('site.vehicles.route')->with(array('pageTitle'=>'Posicionamiento', 'data' => $data));
    }

	/*
	public function index()
	{
		$trackingroutes = Trackingroute::all();

		return View::make('trackingroutes.index', compact('trackingroutes'));
	}

	public function create()
	{
		return View::make('trackingroutes.create');
	}

	public function store()
	{
		$validator = Validator::make($data = Input::all(), Trackingroute::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Trackingroute::create($data);

		return Redirect::route('trackingroutes.index');
	}

	public function show($id)
	{
		$trackingroute = Trackingroute::findOrFail($id);

		return View::make('trackingroutes.show', compact('trackingroute'));
	}

	public function edit($id)
	{
		$trackingroute = Trackingroute::find($id);

		return View::make('trackingroutes.edit', compact('trackingroute'));
	}

	public function update($id)
	{
		$trackingroute = Trackingroute::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Trackingroute::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$trackingroute->update($data);

		return Redirect::route('trackingroutes.index');
	}

	public function destroy($id)
	{
		Trackingroute::destroy($id);

		return Redirect::route('trackingroutes.index');
	}
	*/

}
