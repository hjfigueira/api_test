<?php

namespace App\Http\Mapper;

use Illuminate\Database\Eloquent\Model;

interface ApiMapperInterface
{
    public function updateRules() : array;

    public function update(Model $target, array $data = []):Model;

    public function storeRules() : array;

    public function store(Model $target, array $data = []):Model;

}
