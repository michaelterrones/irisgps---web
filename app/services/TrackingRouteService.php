<?php

class TrackingRouteService {

	public static function allTrackingRoutes()
	{

		$data = TrackingRoute::orderBy('date','DESC')->get();
		return Response::success($data, false, false, ': la información de todas las rutas se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function currentRouteInfo($plate)
	{
		$vehicle = Vehicle::where('plate', $plate)->first();

		if(is_null($vehicle)){
			return Response::invalid(null, true, ": la placa del vehículo no es correcta"); //retorna un response invalido pero no actualiza el Token
		}

		$data = TrackingRoute::leftJoin('route_tasks', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
			->where('tracking_routes.vehicle_id', $vehicle->id)
			->where('tracking_routes.date', '<=', Carbon::now()->endOfDay()->toDateTimeString())
		    ->whereIn('route_tasks.task_state_id', array(1,3))
		    ->distinct()
		    ->orderBy('tracking_routes.date', 'DESC')
		    ->with(
					['tasks.state', 'tasks.customer',
					'tasks.stateHistory.state',
					'passengers.employee.job',
					'passengers.type',
					'comments.user']
					)
		    ->get(array('tracking_routes.*'))->first();

		return Response::success($data, false, false, ': toda la información de ruta actual se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function getRouteInfoByRouteId($id)
	{
		$data = TrackingRoute::leftJoin('route_tasks', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
			->where('tracking_routes.id', $id)
			->where('tracking_routes.date', '<=', Carbon::now()->endOfDay()->toDateTimeString())
		    ->whereIn('route_tasks.task_state_id', array(1,3))
		    ->distinct()
		    ->orderBy('tracking_routes.date', 'ASC')
		    ->with(
					['tasks.state', 'tasks.customer',
					'tasks.stateHistory.state',
					'passengers.employee.job',
					'passengers.type',
					'comments.user']
					)
		    ->get(array('tracking_routes.*'))->first();

		return Response::success($data, false, false, ': toda la información de ruta actual se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function getRouteInfoByPlateAndDate($plate, $date) //date must be in d-m-Y format
	{
		$vehicle = Vehicle::where('plate', $plate)->first();

		if(is_null($vehicle)){
			return Response::invalid(null, true, ": la placa del vehículo no es correcta"); //retorna un response invalido pero no actualiza el Token
		}

		$data = TrackingRoute::leftJoin('route_tasks', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
			->where('tracking_routes.vehicle_id', 3)
			->where('tracking_routes.date', '>=', Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateTimeString())
			->where('tracking_routes.date', '<=', Carbon::createFromFormat('d-m-Y', $date)->endOfDay()->toDateTimeString())
		    ->distinct()
		    ->orderBy('tracking_routes.date', 'DESC')
		    ->with(
					['locationHistories.incidentType',
					'tasks.state', 'tasks.customer',
					'tasks.stateHistory.state',
					'passengers.employee.job',
					'passengers.type',
					'comments.user']
					)
		    ->get(array('tracking_routes.*'))->first();

		if(is_null($data))
		{
			return Response::success($data, false, false, ": no hay información de alguna ruta del vehículo de placa '$plate' a la fecha '$date'"); //retorna un response correcto y genera un Token nuevo
		}

		return Response::success($data, false, false, ": toda la información de ruta del vehículo '$plate' a la fecha '$date' se ha recuperado con éxito"); //retorna un response correcto y genera un Token nuevo
	}

	public static function registerComment($tracking_route_id, $comment)
	{
		$tracking_route = TrackingRoute::find($tracking_route_id);

		$route_comment = new RouteComment;

		$route_comment->user_id = 1;//Auth::user()->id;   eliminar el hardcode cuando se implemente el login
		$route_comment->tracking_route_id = $tracking_route->id;
		$route_comment->comment = $comment;

		$route_comment->save();

		return Response::success(null, false, false, ': se ha registrado el comentario con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function changeRouteTaskState($route_task_id, $task_state_id, $description)
	{
		$route_task = RouteTask::find($route_task_id);
		$route_task->task_state_id = $task_state_id;
		$route_task->description = $description;
		$route_task->save();

		$task_state_history = new TaskStateHistory;
		$task_state_history->route_task_id = $route_task->id;
		$task_state_history->task_state_id = $task_state_id;
		$task_state_history->description = $description;
		$task_state_history->save();

		return Response::success(null, false, false, ': se ha registrado el cambio de estado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

    public static function getTrackingRouteById($tracking_route_id)
    {
        /*$data = Vehicle::with(['locations' => function ($q) {
          $q->orderBy('location_histories.created_at', 'DESC')->first();
        }])->get();*/

        $data = TrackingRoute::with([
            'vehicle',
            'locationHistories',
            'tasks.state',
            'tasks.customer',
            'tasks.stateHistory.state',
            'comments.user'
        ])->find($tracking_route_id);

        if(is_null($data)){
            return Response::invalid(null, true, ": no hay vehículos que buscar"); //retorna un response invalido pero no actualiza el Token
        }

        return Response::success($data, false, false, ': toda la información de ruta actual se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
    }

}