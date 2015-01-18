<?php namespace PhotoMap\Pagination;

class Factory extends \Illuminate\Pagination\Factory {

	/**
	 * Get a new paginator instance.
	 *
	 * @param  array  $items
	 * @param  int    $total
	 * @param  int|null  $perPage
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function make(array $items, $total, $perPage = null)
	{
		$paginator = new Paginator($this, $items, $total, $perPage);

		return $paginator->setupPaginationContext();
	}

}