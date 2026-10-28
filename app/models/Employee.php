<?php

class Employee extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		'dni' 	=> 'required|digits:8|unique:employees,dni',
		'first_name' => 'required',
		'last_name' => 'required',
		'mobile' => 'required|numeric',
		'job_id' => 'required|numeric'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function getUpdateRules()
	{
		return $rules = [
			'dni' 	=> 'required|digits:8|unique:employees,dni'.( is_null($this->id) ? '' : ','.$this->id ),
			'first_name' => 'required',
			'last_name' => 'required',
			'mobile' => 'required|numeric',
			'job_id' => 'required|numeric'
		];
	}

	public function getFullname()
    {
        return $this->first_name.' '.$this->last_name;
    }

	public function passengers()
    {
        return $this->hasMany('Passenger','employee_id','id');
    }

	public function job()
    {
        return $this->belongsTo('Job', 'job_id','id');
    }

}