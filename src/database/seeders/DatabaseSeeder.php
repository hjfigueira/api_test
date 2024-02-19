<?php

namespace Database\Seeders;

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
        $c1 = DB::table('company')->insertGetId([
            'name' => 'ACME Company',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $c2 = DB::table('company')->insertGetId([
            'name' => 'SeCond Company',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $c3 = DB::table('company')->insertGetId([
            'name' => 'III Company',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $fm1 = DB::table('fund_manager')->insertGetId([
            'name' => 'Fund Manager 1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $fm2 = DB::table('fund_manager')->insertGetId([
            'name' => 'Fund Manager 2',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        $f1 = DB::table('fund')->insertGetId([
            'name' => 'HTCXX',
            'start_year' => 2023,
            'created_at' => now(),
            'updated_at' => now(),
            'fund_manager_id' => $fm1
        ]);

        $f2 = DB::table('fund')->insertGetId([
            'name' => 'DWTXX',
            'start_year' => 2021,
            'created_at' => now(),
            'updated_at' => now(),
            'fund_manager_id' => $fm1
        ]);


        $f3 = DB::table('fund')->insertGetId([
            'name' => 'HTCXX',
            'start_year' => 2023,
            'created_at' => now(),
            'updated_at' => now(),
            'fund_manager_id' => $fm1
        ]);

        $f4 = DB::table('fund')->insertGetId([
            'name' => 'EURXX',
            'start_year' => 2024,
            'created_at' => now(),
            'updated_at' => now(),
            'fund_manager_id' => $fm2
        ]);

        $f5 = DB::table('fund')->insertGetId([
            'name' => 'IOPXX',
            'start_year' => 2021,
            'created_at' => now(),
            'updated_at' => now(),
            'fund_manager_id' => $fm2
        ]);

        DB::table('alias')->insertGetId([
            'name' => 'IOPXX_ALIAS',
            'created_at' => now(),
            'updated_at' => now(),
            'fund_id' => $f5
        ]);

        DB::table('alias')->insertGetId([
            'name' => 'IOPXX_ALIAS 2',
            'created_at' => now(),
            'updated_at' => now(),
            'fund_id' => $f5
        ]);

        DB::table('alias')->insertGetId([
            'name' => 'EURXX_ALIAS 2',
            'created_at' => now(),
            'updated_at' => now(),
            'fund_id' => $f4
        ]);

        DB::table('company_fund')->insert(['company_id' => $c1, 'fund_id' => $f1]);
        DB::table('company_fund')->insert(['company_id' => $c2, 'fund_id' => $f1]);
        DB::table('company_fund')->insert(['company_id' => $c1, 'fund_id' => $f2]);
        DB::table('company_fund')->insert(['company_id' => $c2, 'fund_id' => $f2]);
        DB::table('company_fund')->insert(['company_id' => $c3, 'fund_id' => $f3]);
    }
}
