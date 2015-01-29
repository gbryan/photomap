<?php namespace PhotoMap\QueryScopes;

use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\ScopeInterface;
use \Auth;

class MultiTenantScope implements ScopeInterface {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $model = $builder->getModel();

        $builder->where($model->getTenantIdColumn(), '=', Auth::user()->_id);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
        $column = $builder->getModel()->getTenantIdColumn();

        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where)
        {
            if ($column == $where['column'] && '=' == $where['operator'])
            {
                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }

}
