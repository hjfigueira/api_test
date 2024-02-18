<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuplicatedFundCandidate extends Model
{
    use HasFactory;

    protected $table = 'duplicated_fund_candidate';

    public function parent()
    {
        return $this->belongsTo(Fund::class, 'parent_id');
    }

    public function duplicate()
    {
        return $this->belongsTo(Fund::class, 'duplicate_id');
    }
}
