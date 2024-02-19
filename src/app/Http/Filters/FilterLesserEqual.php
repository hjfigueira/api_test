<?php

namespace App\Http\Filters;

use App\Http\Filters\Core\ApiFilter;

class FilterLesserEqual extends ApiFilter
{
    public function apply(string $field, string $value, $query)
    {

        return $query->where($field, '<=', $value);
    }
}
