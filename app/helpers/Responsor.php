<?php

class Responsor {

	public $data = null;
	private $emptyToken = false;		//por defecto siempre se devolverá un token
	public $message = null;
	public $recived_data = null;

	private $status = null;			//Por defecto siempre el response es SUCCESS
	private $array_status = null;			//Por defecto siempre el response es SUCCESS

	private $response_status = null;
	private $response_code = null;
	private $response_valid = null;

	private $response = null;

	const SUCCESS = 'success';
	const NO_AUTH = 'noAuth';
	const INVALID = 'invalid';
	const INTERNAL_ERROR = 'internalError';

	private $data_SUCCESS = array(
		'status'	=> 'success',
		'code'		=> 'irs001',
		'valid'		=> true,
		'message'	=> 'Correcto: '
		);
	private $data_NO_AUTH = array(
		'status'	=> 'noAuth',
		'code'		=> 'irs401',
		'valid'		=> false,
		'message'	=> 'Sin autorización: '
		);
	private $data_INVALID = array(
		'status'	=> 'invalid',
		'code'		=> 'irs402',
		'valid'		=> false,
		'message'	=> 'Datos inválidos: '
		);
	private $data_INTERNAL_ERROR = array(
		'status'	=> 'internalError',
		'code'		=> 'irs501',
		'valid'		=> false,
		'message'	=> 'Error interno: '
		);


	public function __construct()
	{
		$this->isSuccess();			//Por defecto siempre el response es SUCCESS
		$this->compile_response();
	}

	private function compile_response()
	{
		$this->response_status = $this->array_status['status'];
		$this->response_code = $this->array_status['code'];
		$this->response_valid = $this->array_status['valid'];
		//$this->recived_data = ($this->status == self::SUCCESS) ? null : ['header' => Request::header(), 'input' => Input::all()];
		$this->recived_data = ['header' => Request::header(), 'input' => Input::all()];

		$this->response = array(
			'status'	=> $this->response_status,
			'code'		=> $this->response_code,
			'valid'		=> $this->response_valid,
			'message'	=> $this->array_status['message'].$this->message,
			'data'		=> $this->data,
			'empty_token'		=> $this->emptyToken,
			'recived_data'	=> $this->recived_data
			);
	}

	public function isSuccess()
	{
		$this->status = self::SUCCESS;
		$this->array_status = $this->data_SUCCESS;	
	}

	public function isInvalid()
	{
		$this->status = self::INVALID;
		$this->array_status = $this->data_INVALID;	
	}

	public function isNoAuth()
	{
		$this->status = self::NO_AUTH;
		$this->array_status = $this->data_NO_AUTH;	
	}

	public function isInternalError()
	{
		$this->status = self::INTERNAL_ERROR;
		$this->array_status = $this->data_INTERNAL_ERROR;	
	}

	/*
	public function __set($attr, $value)
    {
    	if(in_array($attr, ['data', 'message']))
    	{
        	$this->$attr = $value;
    	}
    }
    */

	public function returnEmptyToken()
	{
		$this->emptyToken = true;
	}

	public function isEmptyToken()
	{
		return $this->emptyToken;
	}

	public function response()
	{
		$this->compile_response();

		if(!JMUserAgent::isAndroidRequest()){			
			return $this->response;
		}
        /*
		if((!is_null(Request::header('Device-Type'))) && (Request::header('Device-Type') == 'mo'))
		{
			$emptyToken = true;
		}
        */

	    return Response::json($this->response, 200, [], JSON_UNESCAPED_UNICODE);
	}
}