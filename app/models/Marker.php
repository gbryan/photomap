<?php

class Marker extends BaseModel {

	public $singularName = 'marker';

	public $pluralName = 'markers';

	protected $fillable = [
		'name',
		'loc',
		'tags',
		'description',
		'images'
	];

	public $validationRules = [
		'name'			=> 'required',
		'loc'			=> 'required|array',
		'tags'			=> 'array',
		'images'		=> 'array'
	];
}
