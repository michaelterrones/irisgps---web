<?php

class Job extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function employees()
    {
        return $this->hasMany('Employee','job_id','id');
    }

}