<?php

class Device extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		'imei' => 'required',
		'activated' => 'boolean|required_with:allowed',
		'allowed' => 'boolean'
	];

	// Don't forget to fill this array
	protected $fillable = [];

    public function getUpdateRules()
    {
        return $rules = [
            'imei' => 'required|unique:devices,mobile'.( is_null($this->id) ? '' : ','.$this->id ),
            'activated' => 'boolean|required_with:allowed',
            'allowed' => 'boolean'
        ];
    }

	public function vehicle()
    {
        return $this->hasMany('Vehicle', 'device_id','id');
    }

	public function getActivated()
    {
        return ($this->activated == 1) ? 'Si' : 'No';
    }

	public function getAllowed()
    {
        return ($this->allowed == 1) ? 'Si' : 'No';
    }

    public static function registerGCM($mobile)
    {
		$device = Device::whereMobile($mobile)->first();

		if(!is_null($device))
		{
			$gcm = Request::header('gcm');

			if($device->gcm != $gcm)
			{
				$device->gcm = $gcm;
				$device->save();
			}
		}

    }

	public static function findByMobile($mobile)
	{
		$device = Device::whereMobile($mobile)->first();

		return $device;
	}

	public function isActivated()
	{
		return ($this->activated == 0) ? false : true;
	}

	public function isAllowed()
	{
		return ($this->allowed == 0) ? false : true;
	}

	public static function generateToken($regenerate=true)
	{
		if($regenerate)
		{
			$device = Device::where('token', Request::header('token'))->where('mobile', Request::header('IMEI'))->first();
		}else{
			$device = Device::where('mobile', Request::header('IMEI'))->first();
		}

		$token = '';

		if(!is_null($device))
		{
			$device->token = Tokenizer::generate();		
			$device->save();
			$token = $device->token;
		}

		return $token;
	}

}