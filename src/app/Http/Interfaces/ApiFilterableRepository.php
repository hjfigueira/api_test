<?php

namespace App\Http\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ApiFilterableRepository
{
    public function getFilterableQuery() : Builder;
}
