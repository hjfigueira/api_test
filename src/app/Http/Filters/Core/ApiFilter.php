<?php

namespace App\Http\Filters\Core;

abstract class ApiFilter
{
    abstract public function apply(string $field, string $value, $query);
}
