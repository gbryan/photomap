<?php namespace PhotoMap\Observers;

use Jenssegers\Mongodb\Model as Model;
use \PhotoMap\Helpers;

class ProjectFilteringObserver {
	
	/**
	 * Fires before model is saved in the database
	 * @param  Model  $model
	 * @return void
	 */
	public function saving(Model $model)
	{
		$projectIdColumn = $model->getProjectIdColumn();
		$model->$projectIdColumn = (!empty(Helpers::currentScope('project')) ? Helpers::currentScope('project')->id : null);
	}

}
