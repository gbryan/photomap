<?php namespace PhotoMap\QueryScopes;

use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\ScopeInterface;
use \PhotoMap\Helpers;
use \Auth;

class ProjectFilteringScope implements ScopeInterface {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        // Constrain query to the current project only if there is a current project specified.
        if (!empty(Helpers::currentScope('project')))
        {
            $model = $builder->getModel();
            $builder->where($model->getProjectIdColumn(), '=', Helpers::currentScope('project')->id);
        }
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
        $column = $builder->getModel()->getProjectIdColumn();

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
