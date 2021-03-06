<?php namespace PhotoMap\Observers;

use Jenssegers\Mongodb\Model as Model;
use \PhotoMap\Helpers;

class MultiTenantObserver {
	
	/**
	 * Fires before model is saved in the database
	 * @param  Model  $model
	 * @return void
	 */
	public function saving(Model $model)
	{
		$tenantIdColumn = $model->getTenantIdColumn();
		$model->$tenantIdColumn = Helpers::currentScope('user')->id;
	}

}
