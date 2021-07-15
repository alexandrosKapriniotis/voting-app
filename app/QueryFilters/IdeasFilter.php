<?php


namespace App\QueryFilters;

class IdeasFilter extends Filter
{
    protected function applyFilter($builder)
    {
        if (request()->ideas_filter === 'top_voted'){
            return $builder->orderByDesc('votes_count');
        } else if (request()->ideas_filter === 'my_ideas')
        {
            return $builder->where('user_id',auth()->id());
        }

        return $builder;
    }
}
