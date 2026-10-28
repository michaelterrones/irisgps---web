<?php

class EmployeeService {

	public static function allEmployees()
	{

		$data = Employee::all();
		return Response::success($data, false, false, ': la información de todos los empleados se ha recuperado con éxito'); //retorna un response correcto y genera un Token nuevo
	}

	public static function createEmployee($input)
	{
		$validator = Validator::make(
		    $input,
		    Employee::$rules
		);
		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Response::invalid(false, false, ": los datos tienen errores", $messages);
		}

		$data =new Employee;
		$data->dni = $input['dni'];
		$data->first_name = $input['first_name'];
		$data->last_name = $input['last_name'];
		$data->mobile = $input['mobile'];
		$data->job_id = $input['job_id'];

		if($data->save())
		{
			return Response::success($data, false, false, ': se ha creado el registro de manera exitosa'); //retorna un response correcto y genera un Token nuevo
		}

		return Response::invalid(false, false, ": no se pudo procesar la solicitud", 'Hubo un error al procesar la solicitud');
	}

}