<?php

class IrisModel extends \Eloquent {
  
	public function __construct(array $attributes = []){

		parent::__construct($attributes);
	}

	public static function boot()
	{
		parent::boot();
		/*
		static::creating(function($model)
		{
			//change to Auth::user() if you are using the default auth provider
			$gasModels = ['GasRelatedDoc', 'GasKardex', 'GasKardexBalance', 'GasInvoiceDetail'];
			$user_id = null;
			$subdomain = (is_null($model->subdomain)) ? null : $model->subdomain;
			if(Auth::check())
			{            
				$user = Auth::user();
				$user_id = $user->id;
				$subdomain = (is_null($user->subdomain) ? MagicRoute::getSubdomain2() : $user->subdomain);
			}
			if(in_array(get_class($model), $gasModels))
			{
				$subdomain = 'trancesa';
			}

			$model->created_by = $user_id;
			$model->updated_by = $user_id;
			$model->subdomain = $subdomain;
		});

		static::updating(function($model)
		{
			//change to Auth::user() if you are using the default auth provider
			$user_id = null;
			if(Auth::check() && MagicRoute::getSubdomain2() == Auth::user()->subdomain)
			{            
				$user = Auth::user();
				$user_id = $user->id;
				$model->updated_by = $user_id;
			}
		});
		*/
	}

    public function getCreatedAtForHumans()
	{
	    return Timesor::diffForHumans($this->created_at);
	}

    public function getCreatedAt()
	{
	    return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y H:i:s');
	}

	public function getUpdatedAt()
	{
	    return Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('d/m/Y H:i:s');
	}

	public static function isBrower()
	{
		$user_agent_info = new UAgentInfo;

		$isWebKit = $user_agent_info->DetectWebkit();
		
		if($isWebKit){
			return true;
		}else
		{
			return false;
		}
	}

	public static function toModel($modelName, $models, $ignoreId = true)
	{
	  Eloquent::unguard();

	  $collection = new Illuminate\Support\Collection;

	  foreach($models as $model) {
	    $newModel = new $modelName;
	    $i = -1;
		$keys = array_keys($model);
	    foreach ($model as $value) {
	    	$i++;
			$key = $keys[$i];	
			if($key == 'created_at' || $key == 'updated_at')
			{
				$validator = Validator::make(
				    $model,
				    [
				    	'created_at'=>'date_format:Y-m-d H:i:s',
				    	'updated_at'=>'date_format:Y-m-d H:i:s',
				    ]
				);
				if ($validator->fails())
				{
					$newModel->$key = Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s');
				}else{
					$newModel->$key = Carbon::createFromFormat('Y-m-d H:i:s', $value);
				}
			}else
			{
				if(!($key == 'id' && $ignoreId))
				{	
					if(Stringtor::endsWith($key, 'id') && $value == 0)
					{
						$newModel->$key = null;
					}else{
						$newModel->$key = $value;
					}					
				}
			}
		}
	    $newModel->exists = true;
	    $collection->push($newModel);
	  }

	  Eloquent::reguard();

	  return $collection;
	}	

	public static function toSaveModel($modelName, $models, $ignoreId = true)
	{
	  Eloquent::unguard();

	  $collection = new Illuminate\Support\Collection;

	  foreach($models as $model) {
	    $newModel = new $modelName;
	    $i = -1;
		$keys = array_keys($model);
	    foreach ($model as $value) {
	    	$i++;
			$key = $keys[$i];	
			if($key == 'created_at' || $key == 'updated_at')
			{
				$validator = Validator::make(
				    $model,
				    [
				    	'created_at'=>'date_format:d/m/Y H:i:s',
				    	'updated_at'=>'date_format:d/m/Y H:i:s'
				    ]
				);
				if ($validator->fails())
				{
					$newModel->$key = Carbon::createFromFormat('Y-m-d H:i:s', $value);
				}else{
					$newModel->$key = Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s');
				}
			}else
			{
				if(!($key == 'id' && $ignoreId))
				{	
					if(Stringtor::endsWith($key, 'id') && $value == 0)
					{
						$newModel->$key = null;
					}else{
						$newModel->$key = $value;
					}					
				}
			}
		}
	    $newModel->save();
	    $collection->push($newModel);
	  }

	  Eloquent::reguard();

	  return $collection;
	}

}