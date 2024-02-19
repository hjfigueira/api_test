<?php

namespace App\Http\ViewModels;

use App\Http\Interfaces\ApiViewModelInterface;
use App\Models\Fund;
use Illuminate\Database\Eloquent\Model;

class FundViewModel implements ApiViewModelInterface
{

    public function __construct(
        private FundManagerViewModel $fundManagerViewModel
    )
    {
    }

    /**
     * Parse details for the single record view
     *
     * @param  Fund $record
     * @return array
     */
    public function detail(Model $record) : array
    {
        return $this->baseFormat($record);
    }


    /**
     * Parse one item in the list context
     *
     * @param  Fund $record
     * @return array
     */
    public function list(Model $record) : array
    {
        return $this->baseFormat($record);
    }


    /**
     * Use a single mapping since they're the same in both contexts
     *
     * @param  Fund $record
     * @return array
     */
    public function baseFormat(Model $record): array
    {
        return [
            'id'           => $record->id,
            'name'         => $record->name,
            'start_year'   => $record->start_year,
            'fund_manager' => $this->fundManagerViewModel->detail($record->manager),
            'aliases'      => $record->aliases->pluck('name'),
            'created_at'   => $record->created_at,
            'updated_at'   => $record->updated_at,
        ];
    }
}
