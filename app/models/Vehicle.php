<?php

class Vehicle extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		'plate' => array('required', 'regex:/[A-Z]{1}[0-9,A-Z]{1}[A-Z]{1}-[0-9]{3}/'),
		'brand' => 'required',
		'model' => 'required',
		'color' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];
/*
	public function unpair()
	{
		$this->device_id = null;
	}*/

    public function getUpdateRules()
    {
        return $rules = [
            'plate' => array('required', 'regex:/[A-Z]{1}[0-9,A-Z]{1}[A-Z]{1}-[0-9]{3}/'),
            'brand' => 'required',
            'model' => 'required',
            'color' => 'required'
        ];
    }

	public function vehicleHistory()
    {
        return $this->hasMany('RouteVehicleHistory','vehicle_id','id');
    }

	public function trackingRoutes()
    {
        return $this->hasMany('TrackingRoute','vehicle_id','id');
    }

    public function locations()
    {
        return $this->hasMany('LocationHistory', 'vehicle_id', 'id');
    }

    public function locationHistories()
    {
        return $this->hasMany('LocationHistory','vehicle_id','id');
    }

	public function device()
    {
		return $this->belongsTo('Device','device_id','id');
    }

    public function hasTrackingRoute($date = null)
    {
    	$date = is_null($date) ? Carbon::now() : Carbon::createFromFormat('d-m-Y', $date);
    	$tracking_route = TrackingRoute::where('date', '>=', $date->startOfDay()->toDateTimeString())
		    ->where('date', '<=', $date->endOfDay()->toDateTimeString())
		    ->where('vehicle_id', $this->id)
		    ->first();

		return (!is_null($tracking_route)) ? $this->getTrackingRouteById($tracking_route->id) : null;
    }

    public function getTrackingRouteById($tracking_route_id)
    {
    	return TrackingRoute::with([
			'tasks.state',
			'tasks.customer',
			'tasks.stateHistory.state',
			'passengers.employee.job',
			'passengers.type',
			'comments.user'
		])->find($tracking_route_id);
    }

    public function hasTrackingRouteLocationHistory($date = null)
    {
    	$date = is_null($date) ? Carbon::now() : Carbon::createFromFormat('d-m-Y', $date);
    	$tracking_route = TrackingRoute::where('date', '>=', $date->startOfDay()->toDateTimeString())
		    ->where('date', '<=', $date->endOfDay()->toDateTimeString())
		    ->where('vehicle_id', $this->id)
		    ->first();

		return (!is_null($tracking_route)) ? $this->getLocationHistoryByTrackingRouteId($tracking_route->id) : null;
    }

    public function getLocationHistoryByTrackingRouteId($tracking_route_id)
    {
    	return LocationHistory::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'location_histories.tracking_route_id')
        ->with([
			'incidentType'
		])
		->where('tracking_route_id', $tracking_route_id)
		->where('vehicle_id', $this->id)
		->orderBy('created_at', 'DESC')
		->get();
    }

    public function hasLocationHistory($date = null)
    {
    	$location_history = $this->getLocationHistoryByDate($date);

		return (!is_null($location_history->first())) ? $location_history : null;
    }

    public function getLocationHistoryByDate($date = null)
    {
    	$date = is_null($date) ? Carbon::now() : Carbon::createFromFormat('d-m-Y', $date);
        return LocationHistory::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'location_histories.tracking_route_id')
            ->with([
                'incidentType'
            ])
            ->where('tracking_routes.vehicle_id', $this->id)
            ->where('location_histories.created_at', '>=', $date->startOfDay()->toDateTimeString())
            ->where('location_histories.created_at', '<=', $date->endOfDay()->toDateTimeString())
            ->orderBy('location_histories.created_at', 'DESC')
            ->select('location_histories.*')
            ->get();
    }

    public function hasLastPosition()
    {
        $last_position = LocationHistory::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'location_histories.tracking_route_id')
            ->with([
                'incidentType'
            ])
            ->where('tracking_routes.vehicle_id', $this->id)
            ->orderBy('location_histories.created_at', 'DESC')
            ->select('location_histories.*')
            ->first();

		return (!is_null($last_position)) ? $last_position : null;
    }

}