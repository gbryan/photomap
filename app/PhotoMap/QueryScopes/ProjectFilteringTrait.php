<?php namespace PhotoMap\QueryScopes;

use \PhotoMap\Helpers;

trait ProjectFilteringTrait {
	
    /**
     * Name of the column that stores a reference to the tenant who owns the document
     * @var string
     */
    protected $projectIdColumn = 'project_id';

    /**
     * Whether the model supports filtering by a particular project
     * @var boolean
     */
    protected static $supportsProjectFiltering = true;

    /**
     * Boot this trait for a model.
     *
     * @return void
     */
    public static function bootProjectFilteringTrait()
    {
        static::addGlobalScope(new ProjectFilteringScope);
    }

    /**
     * Get the query builder without the scope applied.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function allProjects()
    {
        return (new static)->newQueryWithoutScope(new ProjectFilteringScope);
    }

    /**
     * Get the $projectIdColumn.
     * @return string
     */
    public function getProjectIdColumn()
    {
        return $this->projectIdColumn;
    }

}
