<?php

namespace App\Http\Repositories;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Model;

class FundRepository extends Repository
{
    public function getModel() : Model
    {
        return new Fund();
    }
}
