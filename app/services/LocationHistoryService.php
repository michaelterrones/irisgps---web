<?php

class LocationHistoryService {

	public static function storeLocations($plate, $route_id, $locations)
	{
		if(is_null($plate) || is_null($route_id) ||is_null($locations))
		{
			return Response::invalid(null, true, ": los datos no están completos"); //retorna un response invalido pero no actualiza el Token
		}

		//$vehicle = Vehicle::where('plate', $plate)->first();

		//Log::info('Log message', array('context' => "placa: $plate, ruta: $route_id, posiciones: $locations"));

		$locations = json_decode($locations, true);
		$locationHistories = LocationHistory::toSaveModel('LocationHistory', $locations);

		return Response::success($data = null, false, false, ': las posiciones se han registrado con éxito'); //retorna un response correcto y genera un Token nuevo

	}

}