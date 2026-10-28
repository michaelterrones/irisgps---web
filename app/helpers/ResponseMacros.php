<?php

	Response::macro('success', function($data=null, $regenerateToken=true, $emptyToken=false, $message = null)
	{		
		$response = array(
			'status'	=> 'success',
			'code'		=> 'irs001',
			'valid'		=> true,
			'massege'	=> 'Correcto'.$message,
			'data'		=> $data,
			'recived_data'	=> null
		);
		
		if(!JMUserAgent::isAndroidRequest()){
			return $response;
		}

		if((!is_null(Request::header('Device-Type'))) && (Request::header('Device-Type') == 'mo'))
		{
			$regenerateToken = false;
			$emptyToken = true;
		}

	    return Response::json($response, 200, [], JSON_UNESCAPED_UNICODE)->header('token', ($emptyToken) ? '' : Device::generateToken($regenerateToken));
	});
	
	Response::macro('noAuth', function($message = null)
	{	
		$response = array(
			'status'	=> 'error',
			'code'		=> 'irs401',
			'valid'		=> false,
			'massege'	=> 'Sin autorización'.$message,
			'data'		=> null,
			'recived_data'	=> ['header' => Request::header(), 'input' => Input::all()]
		);
		
		if(!JMUserAgent::isAndroidRequest()){
			return $response;
		}

		if((!is_null(Request::header('Device-Type'))) && (Request::header('Device-Type') == 'mo'))
		{
			$regenerateToken = false;
			$emptyToken = true;
		}

	    return Response::json($response, 200, [], JSON_UNESCAPED_UNICODE)->header('token', '');
	});

	Response::macro('invalid', function($regenerateToken=true, $emptyToken=false, $message = null, $data = null)
	{	
		$response = array(
			'status'	=> 'error',
			'code'		=> 'irs402',
			'valid'		=> false,
			'massege'	=> 'Datos inválidos'.$message,
			'data'		=> $data,
			'recived_data'	=> ['header' => Request::header(), 'input' => Input::all()]
		);
		
		if(!JMUserAgent::isAndroidRequest()){
			return $response;
		}

		if((!is_null(Request::header('Device-Type'))) && (Request::header('Device-Type') == 'mo'))
		{
			$regenerateToken = false;
			$emptyToken = true;
		}

	    return Response::json($response, 200, [], JSON_UNESCAPED_UNICODE)->header('token', ($emptyToken) ? '' : Device::generateToken($regenerateToken));
	});

	Response::macro('internalError', function()
	{	
		$response = array(
			'status'	=> 'error',
			'code'		=> 'irs501',
			'valid'		=> false,
			'massege'	=> 'Error interno',
			'data'		=> null,
			'recived_data'	=> ['header' => Request::header(), 'input' => Input::all()]
		);
		
		if(!JMUserAgent::isAndroidRequest()){
			return $response;
		}

		if((!is_null(Request::header('Device-Type'))) && (Request::header('Device-Type') == 'mo'))
		{
			$regenerateToken = false;
			$emptyToken = true;
		}

	    return Response::json($response, 200, [], JSON_UNESCAPED_UNICODE);
	});

