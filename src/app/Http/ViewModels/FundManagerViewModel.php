<?php

namespace App\Http\ViewModels;

use Illuminate\Database\Eloquent\Model;

class FundManagerViewModel implements ApiViewModelInterface
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
            "id" => $record->id,
            "name" => $record->name,
        ];
    }
}
