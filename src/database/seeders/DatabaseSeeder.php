<?php

namespace Database\Seeders;

use App\Models\Alias;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * This can also be done using factories, using DB for simplicity
     */
    public function run(): void
    {
        $c1 = Company::factory()->create();
        $c2 = Company::factory()->create();
        $c3 = Company::factory()->create();

        $fm1 = FundManager::factory()->create();
        $fm2 = FundManager::factory()->create();

        $f1 = Fund::factory()->create([
            'name' => 'HTCXX',
            'start_year' => 2023,
            'fund_manager_id' => $fm1->id
        ]);
        $f2 = Fund::factory()->create([
                'name' => 'DWTXX',
                'start_year' => 2021,
                'fund_manager_id' => $fm1->id
        ]);
        $f3 = Fund::factory()->create([
            'name' => 'HTCXX',
            'start_year' => 2023,
            'fund_manager_id' => $fm1->id
        ]);
        $f4 = Fund::factory()->create([
            'name' => 'EURXX',
            'start_year' => 2024,
            'fund_manager_id' => $fm2->id
        ]);
        $f5 = Fund::factory()->create([
            'name' => 'IOPXX',
            'start_year' => 2021,
            'fund_manager_id' => $fm2->id
        ]);

        Alias::factory()->create([
            'name' => 'IOPXX_ALIAS',
            'fund_id' => $f5->id
        ]);
        Alias::factory()->create([
            'name' => 'IOPXX_ALIAS 2',
            'fund_id' => $f5->id
        ]);
        Alias::factory()->create([
            'name' => 'EURXX_ALIAS 2',
            'fund_id' => $f4->id
        ]);

        DB::table('company_fund')->insert(['company_id' => $c1->id, 'fund_id' => $f1->id]);
        DB::table('company_fund')->insert(['company_id' => $c2->id, 'fund_id' => $f1->id]);
        DB::table('company_fund')->insert(['company_id' => $c1->id, 'fund_id' => $f2->id]);
        DB::table('company_fund')->insert(['company_id' => $c2->id, 'fund_id' => $f2->id]);
        DB::table('company_fund')->insert(['company_id' => $c3->id, 'fund_id' => $f3->id]);
    }
}
