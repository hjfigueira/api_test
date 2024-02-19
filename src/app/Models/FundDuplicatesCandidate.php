<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model class for Fund.
 *
 * @property int $id
 * @property int $parent_id
 * @property int $duplicate_id
 * @property boolean $resolved
 * @property Fund $parent;
 * @property Fund $duplicate;
 */

class FundDuplicatesCandidate extends Model
{
    use HasFactory;

    protected $table = 'fund_duplicated_candidate';


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Fund::class, 'parent_id');
    }


    public function duplicate(): BelongsTo
    {
        return $this->belongsTo(Fund::class, 'duplicate_id');
    }
}
