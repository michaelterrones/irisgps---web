<?php

class LocationHistory extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function vehicle()
    {
        return $this->belongsTo('Vehicle', 'vehicle_id','id');
    }

	public function trackingRoute()
    {
        return $this->belongsTo('TrackingRoute', 'tracking_route_id','id');
    }

    public function incidentType()
    {
        return $this->belongsTo('IncidentType', 'incident_type_id','id');
    }

}