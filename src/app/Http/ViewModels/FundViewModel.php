<?php

namespace App\Http\ViewModels;

use Illuminate\Database\Eloquent\Model;

class FundViewModel implements ApiViewModel
{
    public function details(Model $record) : array
    {
        return $this->baseFormat($record);
    }

    public function list(Model $record) : array
    {
        return $this->baseFormat($record);
    }

    public function baseFormat(Model $record)
    {
        return [
            "name" => $record->name,
            "start_year" => $record->start_year,
            "fund_manager" => $record->manager->name, //@todo eager loading
            "created_at" => $record->created_at,
            "updated_at" => $record->updated_at,
        ];
    }
}
