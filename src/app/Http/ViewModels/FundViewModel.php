<?php

namespace App\Http\ViewModels;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Model;

class FundViewModel implements ApiViewModelInterface
{
    public function details(Model $record) : array
    {
        return $this->baseFormat($record);
    }

    public function list(Model $record) : array
    {
        return $this->baseFormat($record);
    }

    /**
     * @param Fund $record
     * @return array
     */
    public function baseFormat(Model $record)
    {
        $fundManagerViewModel = new FundManagerViewModel();

        return [
            'id'            => $record->id,
            'name'          => $record->name,
            'start_year'    => $record->start_year,
            'fund_manager'  => $fundManagerViewModel->details($record->manager),
            'aliases'       => $record->aliases,
            'created_at'    => $record->created_at,
            'updated_at'    => $record->updated_at,
        ];
    }
}
