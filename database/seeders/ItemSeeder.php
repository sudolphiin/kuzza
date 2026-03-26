<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $items = [
            [
                'name' => 'Set of Text Books (Primary)',
                'description' => 'Full set of textbooks for the current academic year.',
                'category' => 'Books',
                'price' => 2500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'School Uniform (Shirt + Trousers/Skirt)',
                'description' => 'Complete school uniform set in standard sizes.',
                'category' => 'Uniform',
                'price' => 1800.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Writing Materials Kit (Notebooks, Pens, Pencils)',
                'description' => 'Bundle with 5 notebooks, 3 pens, 3 pencils and an eraser.',
                'category' => 'Stationery',
                'price' => 350.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Art & Craft Kit',
                'description' => 'Basic art supplies for classroom activities.',
                'category' => 'Stationery',
                'price' => 450.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('items')->insert($items);
    }
}
