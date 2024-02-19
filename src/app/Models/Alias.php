<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alias extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'alias';


    public function fund()
    {
        return $this->belongsTo(Company::class);
    }
}
