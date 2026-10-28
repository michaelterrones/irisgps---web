<?php

class VehicleService {

	public static function vehiclesLastPositions()
	{
		/*$data = Vehicle::with(['locations' => function ($q) {
		  $q->orderBy('location_histories.created_at', 'DESC')->first();
		}])->get();*/
		$data = Vehicle::all();
		foreach($data as $v)
		{
		    $v->load(array('locations'=>function($q){
		  		$q->orderBy('location_histories.created_at', 'DESC')->first();
			}));
		}

		if(is_null($data)){
			return Response::invalid(null, true, ": no hay vehículos que buscar"); //retorna un response invalido pero no actualiza el Token
		}

		return Response::success($data, false, false, ': toda la información de ruta actual se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function vehicleLastPath($plate)
	{
		/*$data = Vehicle::with(['locations' => function ($q) {
		  $q->orderBy('location_histories.created_at', 'DESC')->first();
		}])->get();*/

		$data = Vehicle::with(['locations' => function ($q) {
			$q->orderBy('location_histories.created_at', 'DESC')->first();
		},	'trackingRoutes' => function ($q) {
			$q->orderBy('tracking_routes.date', 'DESC')->first();
		},
			'trackingRoutes.tasks.state',
			'trackingRoutes.tasks.customer',
			'trackingRoutes.tasks.stateHistory.state',
			'trackingRoutes.passengers.employee.job',
			'trackingRoutes.passengers.type',
			'trackingRoutes.comments.user',
			'trackingRoutes.locationHistories'
		])->wherePlate($plate)->first();

		if(is_null($data)){
			return Response::invalid(null, true, ": no hay vehículos que buscar"); //retorna un response invalido pero no actualiza el Token
		}

		return Response::success($data, false, false, ': toda la información de ruta actual se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function allVehicles()
	{

		$data = Vehicle::all();
		return Response::success($data, false, false, ': la información de todos los vehículos se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function createVehicle($input)
	{
		$validator = Validator::make(
		    $input,
		    Vehicle::$rules
		);
		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Response::invalid(false, false, ": los datos tienen errores", $messages);
		}

		$data =new Vehicle;
		$data->plate = $input['plate'];
		$data->brand = $input['brand'];
		$data->model = $input['model'];
		$data->color = $input['color'];

		if($data->save())
		{
			return Response::success($data, false, false, ': se ha creado el registro de manera exitosa'); //retorna un response correcto y genera un Token nuevo
		}

		return Response::invalid(false, false, ": no se pudo procesar la solicitud", 'Hubo un error al procesar la solicitud');
	}

	public static function editVehicle($input)
	{
		$validator = Validator::make(
		    $input,
		    Vehicle::$rules
		);

		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Response::invalid(false, false, ": los datos tienen errores", $messages);
		}

		$data =new Vehicle;
		$data->plate = $input['plate'];
		$data->brand = $input['brand'];
		$data->model = $input['model'];
		$data->color = $input['color'];

		if($data->save())
		{
			return Response::success($data, false, false, ': se ha creado el registro de manera exitosa'); //retorna un response correcto y genera un Token nuevo
		}

		return Response::invalid(false, false, ": no se pudo procesar la solicitud", 'Hubo un error al procesar la solicitud');
	}

}