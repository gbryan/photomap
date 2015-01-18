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
}
