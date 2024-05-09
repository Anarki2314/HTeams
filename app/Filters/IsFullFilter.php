<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;

class IsFullFilter implements Filter
{
    public function __invoke($query, $value, $filters)
    {
        if ($value) {
            $query->whereHas('members', null, '=', 5);
        } elseif ($value === false) {
            $query->whereHas('members', null, '<', 5);
        }
    }
}
