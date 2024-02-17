<?php

namespace App\Http\ViewModels;

use Illuminate\Database\Eloquent\Model;

interface ApiViewModel
{
    public function details(Model $record) : array;

    public function list(Model $record) : array;
}
