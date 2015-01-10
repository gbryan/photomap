<?php

class BaseModel extends ValidatingModel {


	/**
	 * Returns an array of only the specified fields. If a specified field does not exist on the model, it 
	 * will silently not be included in the returned array.
	 * @param  array  $fields
	 * @return array
	 */
	public function onlyFields(array $fields)
	{
		$fieldsToReturn = array();

		$attributes = $this->toArray();

		foreach ($fields as $field)
		{
			if (array_key_exists($field, $attributes))
			{
				$fieldsToReturn[$field] = $attributes[$field];
			}
		}

		return $fieldsToReturn;
	}

	/**
	 * Returns an array of only the fields that should be returned via the API. These fields are specified
	 * on each model that should limit its exposure through the API.
	 * @return array
	 */
	public function apiFields()
	{
		if (!property_exists($this, 'apiFields'))
		{
			return $this->toArray();
		}

		return $this->onlyFields($this->apiFields);
	}
}