<?php


namespace App\QueryFilters;


use Closure;
use Illuminate\Support\Str;

abstract class Filter
{
    public function handle($builder, Closure $next)
    {
        if(!request()->has($this->filterName())){
            return $next($builder);
        }

        $builder = $next($builder);

        return $next($this->applyFilter($builder));
    }


    protected abstract function applyFilter($builder);


    /**
     * @return string
     */
    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }

}
