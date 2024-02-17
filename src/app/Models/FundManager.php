<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundManager extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fund_manager';

    public function manages()
    {
        return $this->hasMany(Fund::class);
    }
}
