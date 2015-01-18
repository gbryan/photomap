<?php namespace PhotoMap\Pagination;

use \PhotoMap\Extensions\Collection;

class Paginator extends \Illuminate\Pagination\Paginator {

	/**
	 * Hard limit for the number of records that can be returned per page for any paginated query
	 */
	const MAX_PER_PAGE = 500;

	/**
	 * Get the instance as an array containing only the fields specified by $apiFields on the Model.
	 *
	 * @return array
	 */
	public function apiFields()
	{
		$data = $this->toArray();
		$data['data'] = $this->getCollection()->apiFields();
		return $data;
	}

	/**
	 * Get a collection instance containing the items.
	 *
	 * @return \PhotoMap\Extensions\Collection
	 */
	public function getCollection()
	{
		return new Collection($this->items);
	}
}
