<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model class for Fund.
 *
 * @property int $id
 * @property string $name
 * @property int $start_year
 * @property int $fund_manager_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Collection $aliases;
 * @property FundManager $manager;
 */
class Fund extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fund';


    public function manager()
    {
        return $this->belongsTo(FundManager::class, 'fund_manager_id', 'id');
    }


    public function investsIn()
    {
        return $this->hasManyThrough(Company::class, 'company_fund');
    }


    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }


    public function duplicate()
    {
        return $this->belongsToMany(Fund::class, 'fund_duplicated_candidate', 'parent_id', 'duplicate_id');
    }
}
