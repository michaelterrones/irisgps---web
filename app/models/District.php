<?php

class District extends IrisModel {

    use SoftDeletingTrait;

	protected $softDelete = true;

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

}