<?php namespace PhotoMap\Extensions;

class Collection extends \Illuminate\Database\Eloquent\Collection {

	/**
	 * Get the collection with each model containing only the fields specified by its $apiFields property.
	 * @return Collection
	 */
	public function apiFields()
	{
		return $this->map(function($item)
		{
			return $item->apiFields();
		});
	}

	/**
	 * Get the collection with each model containing only the fields specified by the given array.
	 * @param  array $fields
	 * @return Collection
	 */
	public function onlyFields(array $fields)
	{
		return $this->map(function($item) use ($fields)
		{
			return $item->onlyFields($fields);
		});
	}

	/**
	 * Format the data as a geoJSON-formatted array.
	 * @param  boolean $apiFields  Whether the included fields should be limited to those specified by $apiFields on the Model
	 * @return array
	 */
	public function toGeoJson($apiFields = true)
	{
		$data = array();
		
		$data['type'] = 'FeatureCollection';

		if ($apiFields)
		{
			$features = $this->map(function($item) use ($apiFields)
			{
				return $item->toGeoJson($apiFields);
			});

			$data['features'] = $features;
		}

		else {
			$data['features'] = $this->toArray();
		}
		
		return $data;
	}
}
