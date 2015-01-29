<?php namespace PhotoMap\Observers;

use Jenssegers\Mongodb\Model as Model;
use \Auth;

class MultiTenantObserver {
	
	/**
	 * Fires before model is saved in the database
	 * @param  Model  $model
	 * @return void
	 */
	public function saving(Model $model)
	{
		$tenantIdColumn = $model->getTenantIdColumn();
		$model->$tenantIdColumn = Auth::user()->_id;
	}

}
