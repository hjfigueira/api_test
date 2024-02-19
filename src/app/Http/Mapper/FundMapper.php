<?php

namespace App\Http\Mapper;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Model;

class FundMapper implements ApiMapperInterface
{


    /**
     * Parse data from request to the model
     *
     * @param  Fund  $target
     * @param  array $data
     * @return Fund
     */
    public function update(Model $target, array $data = []): Model
    {
        $target->name       = ($data['name'] ?? $target->name);
        $target->start_year = ($data['start_year'] ?? $target->start_year);
        return $target;
    }


    /**
     * Extract data from the request, to the model
     *
     * @param  Fund  $target
     * @param  array $data
     * @return Fund
     */
    public function store(Model $target, array $data = []): Model
    {
        $target->name            = $data['name'];
        $target->start_year      = $data['start_year'];
        $target->fund_manager_id = $data['fund_manager_id'];
        return $target;
    }


    public function updateRules(): array
    {
        return [
            'name'            => [ 'string' ],
            'start_year'      => [
                'integer',
                'digits:4',
            ],
            'fund_manager_id' => [
                'integer',
                'exists:fund_manager,id',
            ],
        ];
    }

    public function storeRules(): array
    {
        return [
            'name'            => [
                'required',
                'string',
            ],
            'start_year'      => [
                'required',
                'digits:4',
            ],
            'fund_manager_id' => [
                'required',
                'exists:fund_manager,id',
            ],
        ];
    }
}
