<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use App\Models\Plan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $userPlans = [
           ['uuid'=>Str::uuid()->toString(),'stripe_plan_id'=>'price_1PmcoTBvltxMBr80XYilI0cN','plan_name'=>'Gold Monthly','plan_price'=>10,'plan_type'=>1,'status'=>1,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")],
           ['uuid'=>Str::uuid()->toString(),'stripe_plan_id'=>'price_1Pmd9bBvltxMBr80UVkSpoce','plan_name'=>'Gold Yearly','plan_price'=>100,'plan_type'=>2,'status'=>1,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
        ];

        if(Plan::count() == 0){
            $insert=Plan::insert($userPlans);
       }
    }
}
