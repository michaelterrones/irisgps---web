<?php

class GeoPositionController extends \BaseController {


	public function getNearLocations($ulat, $ulon, $radius) //necesita correccion no se usara de momento
	{	
		$orig_lat = -12.0879227;
		$orig_lon = -77.0159834;
		$radius = 1; //en km

		$subQuery = DB::table('positions AS distances')->selectRaw("*, 6371 * (2 * atan2(sqrt((sin(((lat - $orig_lat) * pi() / 180)/2) * sin(((lat - $orig_lat) * pi() / 180)/2) + sin(((lon - $orig_lon) * pi() / 180)/2) * sin(((lon - $orig_lon) * pi() / 180)/2) * cos(($orig_lat * pi() / 180)) * cos((lat * pi() / 180)))), sqrt(1-(sin(((lat - $orig_lat) * pi() / 180)/2) * sin(((lat - $orig_lat) * pi() / 180)/2) + sin(((lon - $orig_lon) * pi() / 180)/2) * sin(((lon - $orig_lon) * pi() / 180)/2) * cos(($orig_lat * pi() / 180)) * cos((lat * pi() / 180)))))) as distance")
		                ;

		$query = Position::from(\DB::raw(' ( ' . $subQuery->toSql() . ' ) AS distances '))
		                ->where('distances.distance', '<', $radius)
		                ->select('lat', 'lon', 'name') //elegir los datos que serán devueltos
		                ->get();

		return(json_encode($query));
	}

	public function postRegisterLocations()
	{
		try {

			$token = Input::get('token');

			$locations = json_decode(Input::get('data'), true);

			$vehicle_id = Vehicle::where('token', $token)->first()->id;

			foreach ($locations as $location) {
				$lh = new LocationHistory;
				$lh->vehicle_id = $vehicle_id;
				$lh->lat = $location->lat;
				$lh->lng = $location->lng;
				$lh->save();
			}

			return Response::success();

		} catch (Exception $e) {
			return Response::internalError();
		}
	}

}
