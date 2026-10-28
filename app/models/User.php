<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	// Add your validation rules here
	public static $rules = [
		'first_name' => 'required',
		'last_name' => 'required',
		'email' => 'required|email|unique:users,email',
		'user_type_id' => 'required|numeric',
		'password' => 'required',
	];

	public function getUpdateRules()
	{
		return 	$rules = [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email|unique:users,email'.( is_null($this->id) ? '' : ','.$this->id ),
			'user_type_id' => 'required|numeric',
			'password' => ''
		];
	}

	public function getFullName()
	{
		return $this->first_name.' '.$this->last_name;
	}

    public function type()
    {
        return $this->belongsTo('UserType','user_type_id','id');
    } 

	public function isUser()
	{
		return ($this->user_type_id == 3);
	}

	public function getRememberToken()
    {   
    return $this->remember_token;
    }

    public function setRememberToken($value)
    {
    $this->remember_token = $value;
    }   

    public function getRememberTokenName()
    {
    return 'remember_token';
    }

	public static function getUserList()
	{
		return User::select(DB::raw('id, concat(first_name, " ", last_name) as full_name'))->lists('full_name', 'id');
	}

}
