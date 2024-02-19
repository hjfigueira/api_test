<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuplicatedFundCandidate extends Model
{
    use HasFactory;

    protected $table = 'duplicated_fund_candidate';


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Fund::class, 'parent_id');
    }


    public function duplicate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fund::class, 'duplicate_id');
    }
}
