<?php

class IncidentType extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];


	public function locationHistories()
    {
        return $this->hasMany('LocationHistory', 'incident_type_id','id');
    }

}