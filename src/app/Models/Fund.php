<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fund extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fund';

    protected $fillable = ['name','start_year','fund_manager_id'];


    public function manager()
    {
        return $this->belongsTo(FundManager::class, 'fund_manager_id','id');
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
