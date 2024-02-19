<?php

namespace App\Http\ViewModels;

use App\Http\Interfaces\ApiViewModelInterface;
use App\Models\FundDuplicatesCandidate;
use Illuminate\Database\Eloquent\Model;

class FundDuplicatesViewModel implements ApiViewModelInterface
{

    public function __construct(
        private FundViewModel $fundViewModel
    )
    {
    }


    /**
     * Parse details for the single record view
     *
     * @param  FundDuplicatesCandidate $record
     * @return array
     */
    public function detail(Model $record) : array
    {
        return [
            'id'   => $record->id,
            'parent' => $this->fundViewModel->detail($record->parent),
            'duplicate' => $this->fundViewModel->detail($record->duplicate),
            'resolved' => $record->resolved
        ];
    }


    /**
     * Parse one item in the list context
     *
     * @param  FundDuplicatesCandidate $record
     * @return array
     */
    public function list(Model $record) : array
    {
        return [
            'id'   => $record->id,
            'parent_id' => $record->parent_id,
            'duplicate_id' => $record->duplicate_id,
            'resolved' => $record->resolved
        ];
    }
}
