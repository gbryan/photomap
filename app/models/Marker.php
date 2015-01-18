<?php

class Marker extends BaseModel {

	protected $apiFields = [];

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

	public function photos()
	{
		return $this->hasMany('Photo');
	}

	public function scopeWithinBox($query, array $coordinates)
	{
		return $query->whereRaw([
			'loc' => [
				'$geoWithin' => [
					'$box' => $coordinates
				]
			]
		]);
	}
}
