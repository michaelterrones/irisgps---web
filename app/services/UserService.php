<?php

class UserService {

	public static function login($email, $password)
	{		
		$u = array(
            'email' => $email,
            'password' => $password
        );

        if (Auth::validate($u)) {
        	$user = User::where('email', $u['email'])->first();
	        Auth::login($user);

	        //return Redirect::route('home.index');
	        //return 'login';
			return Response::success(true, false, false, ': se ha iniciado la sesión correctamente'); //retorna un response correcto y genera un Token nuevo

        }
        else {
        	//return 'datos erroneos';
			return Response::invalid(false, false, ": no se pudo procesar la solicitud", 'Usuario o contraseña incorrectos');
        }
	}

	public static function logout()
	{
		if(Auth::check()){
			Auth::logout();
			//return 'logout';//Redirect::action('SiteController@getLogin');
			return Response::success(null, false, false, ': se ha cerrado la sesión correctamente'); //retorna un response correcto y genera un Token nuevo
		}
		
		return Response::invalid(false, false, ': no existe una sesión que cerrar', 'No existe una sesión que cerrar');
	}


	public static function authCheck()
	{
		$data = Auth::check();
		if($data)
		{
			$message = ': si existe una sesión para el usuario.';

		}else{
			$message = ': no existe una sesión para el usuario.';

		}

		return Response::success($data, false, false, $message); //retorna un response correcto y genera un Token nuevo

	}
	
	public static function allUsers()
	{
		return $data = User::all();
	}

	public static function createUser($input)
	{
		$validator = Validator::make(
		    $input,
		    User::$rules
		);
		if ($validator->fails())
		{
			$messages = $validator->errors()->toArray();
			return Response::invalid(false, false, ": los datos tienen errores", $messages);
		}

		$data =new User;
		$data->first_name = $input['first_name'];
		$data->last_name = $input['last_name'];
		$data->email = $input['email'];
		$data->user_type_id = $input['user_type_id'];
		$data->company_id = 1;
		$data->password = Hash::make($input['password']);

		if($data->save())
		{
			return true;
		}

		return false;
	}

}