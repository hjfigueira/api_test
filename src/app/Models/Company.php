<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id;
 * @property string $name;
 */
class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'company';


    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }


    public function investedBy()
    {
        return $this->hasManyThrough(Fund::class, 'company_fund');
    }
}
