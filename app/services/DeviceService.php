<?php

class DeviceService {

	public static function allDevices()
	{

		$data = Device::all();
		return Response::success($data, false, false, ': la información de todos los dispositivos se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function createDevice($input)
	{
		$validator = Validator::make(
		    $input,
		    Device::$rules
		);
		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Response::invalid(false, false, ": los datos tienen errores", $messages);
		}

		$data =new Device;
		$data->mobile = $input['imei'];
		$data->activated = is_null($input['activated']) ? 0 : 1;
		$data->allowed = is_null($input['allowed']) ? 0 : 1;

		if($data->save())
		{
			return Response::success($data, false, false, ': se ha creado el registro de manera exitosa'); //retorna un response correcto y genera un Token nuevo
		}

		return Response::invalid(false, false, ": no se pudo procesar la solicitud", 'Hubo un error al procesar la solicitud');
	}

	public static function pairDevice($mobile, $plate, $dni)
	{
		$vehicles = Vehicle::where('plate', $plate);

		$vehicle = $vehicles->first();

		if(is_null($vehicle)){
			return Response::invalid(null, true, ": la placa del vehículo no es correcta"); //retorna un response invalido pero no actualiza el Token
		}

		$vehicle = $vehicles->whereNull('device_id')->first();

		if(is_null($vehicle)){
			return Response::invalid(null, true, ": el vehículo ya ha sido pareado"); //retorna un response invalido pero no actualiza el Token
		}

		$employee = Employee::where('dni', $dni)->first();

		if(is_null($employee)){
			return Response::invalid(null, true, ": el conductor no existe"); //retorna un response invalido pero no actualiza el Token
		}

		$device = Device::where('mobile', $mobile)->first();

		$tracking_route = TrackingRoute::where('vehicle_id', $vehicle->id)
			->where('date', '<', Carbon::now()->toDateTimeString())
			->orderBy('date', 'DESC')
			->first();

		$passenger = $tracking_route->passengers()
			->where('tracking_route_id', $tracking_route->id)
			->where('employee_id', $employee->id)
			->first();

		if(is_null($passenger)){
			return Response::noAuth(': el conductor no está autorizado'); //retorna un response invalido pero no actualiza el Token
		}
		
		$vehicle->device_id = $device->id;
		$vehicle->save();
		
		$data = TrackingRoute::with(
			['tasks.state', 'tasks.customer',
			'tasks.stateHistory',
			'passengers.employee.job',
			'passengers.type',
			'comments.user']
			)->find($tracking_route->id);

		return Response::success($data, false, false, ': dispositivo pareado correctamente'); //retorna un response correcto y genera un Token nuevo
	}

	public static function unpairDevice($mobile)
	{		

		$device = Device::where('mobile', $mobile)->first();

		$vehicle = Vehicle::where('device_id', $device->id)->first();

		if(is_null($vehicle) || is_null($mobile)){
			return Response::invalid(null, true, ': el dispositivo no está pareado'); //retorna un response invalido pero no actualiza el Token
		}

		$vehicle->device_id = null;
		$device->token = null;
		$vehicle->save();
		$device->save();

		return Response::success(null, null, true, ': el dispositivo se ha despareado con éxito'); //retorna un response correcto y no genera ningun Token
	}

	public static function getPairedVehiclePlateByToken($token)
	{
		$device = Device::where('token', $token)->first();

		$vehicle = Vehicle::whereDeviceId($device->id)->first();

		if(is_null($vehicle) || is_null($device)){
			return null; // retorna vacío si no pudo encontrar el vehiculo buscando el dispositivo por el token
		}

		return $vehicle->plate;
	}

}