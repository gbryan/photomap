<?php namespace PhotoMap\Observers;

use Jenssegers\Mongodb\Model as Model;

class ValidationObserver {
	
	public function creating(Model $model)
	{
		$rules = $model->getRules('creating');
		$messages = $model->getMessages('creating');
		return $model->validate($model->attributes, $rules, $messages);
	}

	public function updating(Model $model)
	{
		$rules = $model->getRules('updating');
		$messages = $model->getMessages('updating');
		return $model->validate($model->attributes, $rules, $messages);
	}

}
