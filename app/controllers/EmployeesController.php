<?php

class EmployeesController extends \BaseController {

	public function getAllEmployees()
	{
		$response = EmployeeService::allEmployees();
		$data = $response['data'];

		$actionButton = array(
			'route'=>'getCreateEmployee',
			'title'=>'Agregar Nuevo',
			'class'=>'info'
		);

		return View::make('site.employees.all')->with(array('pageTitle'=>'Empleados', 'actionButton'=>$actionButton, 'data' => $data));
	}

	public function getCreateEmployee()
	{

		$actionButton = array(
			'route'=>'getAllEmployees',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateEmployee', $routeParams = null /*['getAllVehicles', true]*/, $method = 'POST', $legend = 'Registrar nuevo empleado', $submitName = 'Agregar', $resetName = null);
		$templator->addText($id = 'dni', $label = 'DNI', $name = 'dni', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addText($id = 'first_name', $label = 'Nombres', $name = 'first_name', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addText($id = 'last_name', $label = 'Apellidos', $name = 'last_name', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addText($id = 'mobile', $label = 'Celular', $name = 'mobile', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null);
		$templator->addSelectBasic($id = 'job_id', $label = 'Puesto', $name = 'job_id', $elements = Job::all()->lists('name', 'id'), $haveEmptyOption = false);

		return View::make('site.CRUD.create-basic')->with(array('pageTitle'=>'Empleados', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postCreateEmployee()
	{
		$input = Input::only('dni', 'first_name', 'last_name', 'mobile', 'job_id');

		$response = EmployeeService::createEmployee($input);

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
				return Redirect::route('getAllEmployees')->withSuccess('Empleado registrado satisfactoriamente');
			}
		}

		return $response;
	}

	public function getEditEmployee($employee_id)
	{
        $employee = Employee::findOrFail($employee_id);

		$actionButton = array(
			'route'=>'getAllEmployees',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postEditEmployee', $routeParams = [$employee->id] , $method = 'POST', $legend = 'Editar empleado', $submitName = 'Guardar', $resetName = null);
		$templator->addText($id = 'dni', $label = 'DNI', $name = 'dni', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null, $value = $employee->dni);
		$templator->addText($id = 'first_name', $label = 'Nombres', $name = 'first_name', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null, $value = $employee->first_name);
		$templator->addText($id = 'last_name', $label = 'Apellidos', $name = 'last_name', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null, $value = $employee->last_name);
		$templator->addText($id = 'mobile', $label = 'Celular', $name = 'mobile', $placeholder = '', $required = true, $autocomplete=false, $helpblock = null, $value = $employee->mobile);
		$templator->addSelectBasic($id = 'job_id', $label = 'Puesto', $name = 'job_id', $elements = Job::all()->lists('name', 'id'), $haveEmptyOption = false, $selectedElement = $employee->job_id);

		return View::make('site.CRUD.create-basic')->with(array('pageTitle'=>'Empleados', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postEditEmployee($employee_id)
	{
        $employee = Employee::findOrFail($employee_id);

		$input = Input::only('dni', 'first_name', 'last_name', 'mobile', 'job_id');

        $validator = Validator::make(
            $input,
            $employee->getUpdateRules()
        );

        if ($validator->fails())
        {
            $messages = $validator->errors()->toArray();
            return Redirect::back()->withValidationErrors($messages);
        }

        $employee->dni = $input['dni'];
        $employee->first_name = $input['first_name'];
        $employee->last_name = $input['last_name'];
        $employee->mobile = $input['mobile'];
        $employee->job_id = $input['job_id'];

        $employee->save();

        return Redirect::back()->withSuccess('Empleado editador satisfactoriamente');
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