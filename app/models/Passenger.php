<?php

class Passenger extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function trackingRoute()
    {
        return $this->belongsTo('TrackingRoute', 'tracking_route_id','id');
    }

	public function employee()
    {
        return $this->belongsTo('Employee', 'employee_id','id');
    }

	public function type()
    {
        return $this->belongsTo('PassengerType', 'passenger_type_id', 'id');
    }

}