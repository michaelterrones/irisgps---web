<?php

class TrackingRoute extends IrisModel {

	// Add your validation rules here
	public static $rules = [
        'date' => 'required|date_format:d/m/Y',
        'vehicle_id' => 'required|numeric',
        'customers' => 'required|array|between:2,7',
		'passengers' => 'required|array|min:1',
	];

	// Don't forget to fill this array
	protected $fillable = [];

    public function getUpdateRule(){
        return $rules = [
            'date' => 'required|date_format:d/m/Y|unique_with:tracking_routes,vehicle_id'.( is_null($this->id) ? '' : ','.$this->id ),
            'user_id' => 'required|numeric',
            'customers' => 'required|array|between:1,7',
		    'passengers' => 'required|array|min:1',
        ];
    }

    public function getDate(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date)->format('d/m/Y H:i:s');
    }

    public function passengers()
    {
        return $this->hasMany('Passenger', 'tracking_route_id','id');
    }

	public function getDriverId()
    {
        return $this->passengers()->where('passenger_type_id', 1)->first()->id;
    }

    public function customers()
    {
        return $this->belongsToMany('Customer', 'route_tasks');
    }

    public function employees()
    {
        return $this->belongsToMany('Employee', 'passengers');
    }

	public function tasks()
    {
        return $this->hasMany('RouteTask','tracking_route_id','id');
    }

    public function comments()
    {
        return $this->hasMany('RouteComment','tracking_route_id','id');
    }

    public function locationHistories()
    {
        return $this->hasMany('LocationHistory','tracking_route_id','id');
    }

	public function lastLocation()
    {
        return $this->hasMany('LocationHistory')->orderBy('created_at', 'DESC')->take(1);
    }

    public function vehicle()
    {
        return $this->belongsTo('Vehicle','vehicle_id','id');
    }

    public function vehicleHistory()
    {
        return $this->hasMany('RouteVehicleHistory','tracking_route_id','id');
    }

    public function hasLastPosition()
    {
        $last_position = LocationHistory::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'location_histories.tracking_route_id')
            ->with([
                'incidentType'
            ])
            ->where('tracking_routes.id', $this->id)
            ->orderBy('location_histories.created_at', 'DESC')
            ->select('location_histories.*')
            ->first();

        return (!is_null($last_position)) ? $last_position : null;
    }

}