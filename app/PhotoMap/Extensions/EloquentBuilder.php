<?php namespace PhotoMap\Extensions;

use PhotoMap\Pagination\Paginator;

class EloquentBuilder extends \Jenssegers\Mongodb\Eloquent\Builder {

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int    $perPage
	 * @param  array  $columns
	 * @return \PhotoMap\Pagination\Paginator
	 */
	public function paginate($perPage = 100, $columns = array('*'))
	{
		$perPage = ($perPage > Paginator::MAX_PER_PAGE ? Paginator::MAX_PER_PAGE : $perPage);

		$paginator = parent::paginate($perPage, $columns);

		return $paginator;
	}
}
