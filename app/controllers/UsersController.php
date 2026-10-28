<?php

class UsersController extends \BaseController {

	public function getUsersLastLocations()
	{
		$response = UserService::usersLastLocations();

		if(!JMUserAgent::isAndroidRequest()){
			$data = $response['data'];
			return View::make('site.users.positions')->with(array('pageTitle'=>'Localización', 'data' => $data));
		}

		return $response;

	}

	public function getUserLastTrackingRoute($id)
	{
		$response = UserService::userLastTrackingRoute($id);
		$data = $response['data'];
		return View::make('site.users.route')->with(array('pageTitle'=>'Visitas del Usuario', 'data' => $data));
	}

	public function getAllUsers()
	{
		$result = UserService::allUsers();

		$actionButton = array(
			'route'=>'getCreateUser',
			'title'=>'Agregar Nuevo',
			'class'=>'info'
		);

		return View::make('site.users.all')->with(array('pageTitle'=>'Usuarios', 'actionButton'=>$actionButton, 'data' => $result));
	}

	public function getCreateUser()
	{

		$actionButton = array(
			'route'=>'getAllUsers',
			'title'=>'Regresar',
			'class'=>'default'
		);

		$templator = new Templator;

		$templator->createForm($actionRoute = 'postCreateUser', $routeParams = ['getAllUsers', true], $method = 'POST', $legend = 'Crear nuevo usuario', $submitName = 'Agregar', $resetName = null);
		$templator->addSelectBasic($id = 'user_type_id', $label = 'Tipo', $name = 'user_type_id', $elements = UserType::all()->lists('name','id'), $haveEmptyOption = false);
		$templator->addText($id = 'first_name', $label = 'Nombres', $name = 'first_name', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null);
		$templator->addText($id = 'last_name', $label = 'Apellidos', $name = 'last_name', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null);
		$templator->addText($id = 'email', $label = 'Email', $name = 'email', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null);
		$templator->addPassword($id = 'password', $label = 'Contraseña', $name = 'password', $required = true);

		return View::make('site.CRUD.basic-form')->with(array('pageTitle'=>'Usuarios', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postCreateUser()
	{
		$input = Input::only('first_name', 'last_name', 'user_type_id', 'email', 'password');

		$result = UserService::createUser($input);

		if(!$result)
		{
			if(JMUserAgent::isWebRequest()){
				return Redirect::back()->withError('Hubo un error al procesar la acción')->withInput();
			}
			$responsor = new Responsor;
			$responsor->isInvalid();
			$responsor->message = "no se pudo procesar la solicitud";
			return $responsor->response();
		}else{
			if(JMUserAgent::isWebRequest()){
				return Redirect::back()->withSuccess('se ha creado el registro satisfactoriamente');
			}
			$responsor = new Responsor;
			$responsor->isSuccess();
			$responsor->message = "se ha creado el registro satisfactoriamente";
			return $responsor->response();
		}
	}

	public function getEditUser($user_id = null)
	{
		$isProfile = false;

		if(is_null($user_id))
		{
			$user = Auth::user();
			$phrase = 'perfil';
			$routeParamsValues = null;
			$route = 'postEditProfile';

			$actionButton = null;

			$isProfile = true;

		}else{
			$user = User::findOrFail($user_id);
			$phrase = 'usuario';
			$routeParamsValues = $user->id;
			$route = 'postEditUser';

			$actionButton = array(
				'route'=>'getAllUsers',
				'title'=>'Regresar',
				'class'=>'default'
			);

		}

		$templator = new Templator;

		$templator->createForm($actionRoute = $route, $routeParams = array($routeParamsValues), $method = 'POST', $legend = 'Editar '.$phrase, $submitName = 'Guardar', $resetName = null);

		if($isProfile)
		{
			$templator->addSelectBasic($id = 'user_type_id_dis', $label = 'Tipo', $name = 'user_type_id_dis', $elements = UserType::all()->lists('name','id'), $haveEmptyOption = false, $selected = $user->user_type_id, $disabled=true);
			$templator->addHidden($id = 'user_type_id', $name = 'user_type_id', $value = $user->user_type_id);
		}else{
			$templator->addSelectBasic($id = 'user_type_id', $label = 'Tipo', $name = 'user_type_id', $elements = UserType::all()->lists('name','id'), $haveEmptyOption = false, $selected = $user->user_type_id);
		}

		$templator->addText($id = 'first_name', $label = 'Nombres', $name = 'first_name', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null, $value = $user->first_name);
		$templator->addText($id = 'last_name', $label = 'Apellidos', $name = 'last_name', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null, $value = $user->last_name);
		$templator->addText($id = 'email', $label = 'Email', $name = 'email', $placeholder = null, $required = true, $autocomplete=false, $helpblock = null, $value = $user->email);
		$templator->addPassword($id = 'password', $label = 'Contraseña', $name = 'password', $required = false);

		return View::make('site.CRUD.basic-form')->with(array('pageTitle'=>'Usuarios', 'actionButton'=>$actionButton, 'templator' => $templator));
	}

	public function postEditUser($user_id = null)
	{
		if(is_null($user_id))
		{
			$user = Auth::user();
		}else{
			$user = User::findOrFail($user_id);
		}

		$input = Input::all();

		$validator = Validator::make(
		    $input,
		    $user->getUpdateRules()
		);

		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Redirect::back()->withValidationErrors($messages)->withInput();
		}

		$user->first_name = $input['first_name'];
		$user->last_name = $input['last_name'];
		$user->email = $input['email'];
		$user->user_type_id = $input['user_type_id'];

		if(!empty(trim($input['password'])))
		{
			$user->password = Hash::make($input['password']);
		}

		if($user->save())
		{
			return Redirect::back()->withSuccess('Registro actualizado correctamente')->withInput();
		}

		return Redirect::back()->withError('No se pudo actualizar el registro')->withInput();

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