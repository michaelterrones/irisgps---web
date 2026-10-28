<?php

class RouteTask extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
    protected $fillable = [];

	public function trackingRoute()
    {
        return $this->belongsTo('TrackingRoute','tracking_route_id','id');
    }

	public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id','id');
    }

	public function state()
    {
        return $this->belongsTo('TaskState', 'task_state_id', 'id');
    }

	public function stateHistory()
    {
        return $this->hasMany('TaskStateHistory', 'route_task_id', 'id');
    }

}