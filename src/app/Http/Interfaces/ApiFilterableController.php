<?php

namespace App\Http\Interfaces;

interface ApiFilterableController
{
    public function getFilters() : array;
}
