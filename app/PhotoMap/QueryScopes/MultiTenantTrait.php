<?php namespace PhotoMap\QueryScopes;

trait MultiTenantTrait {
	
    /**
     * Name of the column that stores a reference to the tenant who owns the document
     * @var string
     */
    protected $tenantIdColumn = 'user_id';

    /**
     * Whether the model supports multi-tenant functionality
     * @var boolean
     */
    protected static $supportsMultiTenant = true;

    /**
     * Boot this trait for a model.
     *
     * @return void
     */
    public static function bootMultiTenantTrait()
    {
        static::addGlobalScope(new MultiTenantScope);
    }

    /**
     * Get the query builder without the scope applied.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function allTenants()
    {
        return (new static)->newQueryWithoutScope(new MultiTenantScope);
    }

}
