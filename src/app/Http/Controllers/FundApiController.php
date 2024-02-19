<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;

class FundApiController extends ApiController
{


    public function getFilters() : array
    {
        return [
            'year'    => [
                'equal',
                'not',
                'greater',
                'lesser',
            ],
            'name'    => [
                'equal',
                'not',
            ],
            'manager' => [
                'equal',
                'not',
            ],
        ];
    }
}
