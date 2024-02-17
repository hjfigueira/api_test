<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    public function manager()
    {
        return $this->hasOne(FundManager::class);
    }

    public function investsIn()
    {
        return $this->hasManyThrough(Company::class, 'company_fund');
    }

    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }
}
