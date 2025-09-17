<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\coupons;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Coupon::truncate();
        Coupon::factory()->count(20)->create();
    }
}
