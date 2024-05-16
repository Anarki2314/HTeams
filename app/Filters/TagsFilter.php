<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;

class TagsFilter implements Filter
{
    public function __invoke($query, $value, $filters)
    {
        $query->whereHas('tags', function ($q) use ($value) {
            if (!is_array($value)) {
                $value = [$value];
            }
            $q->whereIn('tag_id', $value);
        });
    }
}
