<?php

namespace App\Http\ViewModels;

use App\Http\Interfaces\ApiViewModelInterface;
use App\Models\FundManager;
use Illuminate\Database\Eloquent\Model;

class FundManagerViewModel implements ApiViewModelInterface
{

    /**
     * Parse details for the single record view
     *
     * @param  FundManager $record
     * @return array
     */
    public function detail(Model $record) : array
    {
        return $this->baseFormat($record);
    }


    /**
     * Parse one item in the list context
     *
     * @param  FundManager $record
     * @return array
     */
    public function list(Model $record) : array
    {
        return $this->baseFormat($record);
    }


    /**
     * Since for this use-case both responses are the same, use a single mapping.
     *
     * @param  FundManager $record
     * @return array
     */
    public function baseFormat(Model $record): array
    {
        return [
            'id'   => $record->id,
            'name' => $record->name,
        ];
    }
}
