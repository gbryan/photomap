<?php

use \PhotoMap\QueryScopes\MultiTenantTrait;

class Project extends BaseModel {

	use MultiTenantTrait;

	protected $apiFields = [];

	public $singularName = 'project';

	public $pluralName = 'projects';

	protected $fillable = [
		'name',
		'description',
	];

	public $validationRules = [
		'name'		=> 'required',
	];


}
