<?php

class RouteComment extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function trackingRoute()
    {
        return $this->belongsTo('TrackingRoute', 'tracking_route_id', 'id');
    }

	public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

}