<?php

use \PhotoMap\QueryScopes\MultiTenantTrait;

class Marker extends BaseModel {

	use MultiTenantTrait;

	protected $apiFields = [];

	public $singularName = 'marker';

	public $pluralName = 'markers';

	protected $withPhotos = false;

	protected $fillable = [
		'type',
		'name',
		'description',
		'tags',
		'images',
		'geometry',
	];

	public $validationRules = [
		'type'						=> 'required|in:Feature',
		'name'						=> 'required',
		'tags'						=> 'array',
		'geometry'					=> 'required|array',
		'images'					=> 'array'
	];

	public function photos()
	{
		return $this->hasMany('Photo');
	}

	public function scopeWithinBox($query, array $coordinates)
	{
		return $query->whereRaw([
			'geometry' => [
				'$geoWithin' => [
					'$box' => $coordinates
				]
			]
		]);
	}

	/**
	 * Format the data as a geoJSON-formatted array.
	 * @param  boolean $apiFields  Whether the included fields should be limited to those specified by $apiFields on the Model
	 * @return array
	 */
	public function toGeoJson($apiFields = true)
	{
		$geoJson = array();

		if ($apiFields)
		{
			$properties = $this->apiFields();
		}

		else {
			$properties = $this->toArray();
		}

		unset($properties['type']);
		unset($properties['geometry']);

		$geoJson['type'] = $this->type;
		$geoJson['geometry'] = $this->geometry;
		$geoJson['properties'] = $properties;

		return $geoJson;
	}

	public function apiFields()
	{
		$fields = parent::apiFields();

		if ($this->withPhotos)
		{
			$fields['photos'] = $this->photos()->get()->apiFields();
		}

		return $fields;
	}

	public function withPhotos()
	{
		$this->withPhotos = true;
		return $this;
	}

	public function withoutPhotos()
	{
		$this->withPhotos = false;
		return $this;
	}
}
