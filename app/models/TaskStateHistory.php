<?php

class TaskStateHistory extends IrisModel {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	public function task()
    {
        return $this->belongsTo('RouteTask', 'route_task_id', 'id');
    }

	public function state()
    {
        return $this->belongsTo('TaskState', 'task_state_id', 'id');
    }

}