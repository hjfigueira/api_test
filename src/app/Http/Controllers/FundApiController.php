<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;
use App\Http\Filters\Core\ApiFilterable;
use App\Http\Interfaces\ApiFilterableController;

class FundApiController extends ApiController implements ApiFilterableController
{
    use ApiFilterable;

    public function getFilters() : array
    {
        return [
            'start_year'    => [
                'equal',
                'lessEq',
            ],
            'name'    => [
                'equal',
            ],
            'fund_manager_id' => [
                'equal',
            ],
        ];
    }
}
