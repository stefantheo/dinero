<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Apartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('apartments')->insert([
            [
                'account_id' => Account::first()->id,
                'user_id' => 2, // 'Alice'
                'number' => 1,
                'adult_number' => '2',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 1, // 'Mihai Badea'
                'number' => 10,
                'adult_number' => '2',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 3, // 'Bogdan'
                'number' => 2,
                'adult_number' => '2',
                'children_number' => 1,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 4, // 'Mikele'
                'number' => 3,
                'adult_number' => '2',
                'children_number' => 1,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 5, // 'Catalin'
                'number' => 4,
                'adult_number' => '1',
                'children_number' => 1,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 6, // 'Larisa'
                'number' => 5,
                'adult_number' => '2',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 7, // 'Mihai'
                'number' => 6,
                'adult_number' => '1',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 8, // 'Radu'
                'number' => 7,
                'adult_number' => '2',
                'children_number' => 3,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 9, // 'Cristi'
                'number' => 8,
                'adult_number' => '2',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 10, // 'Victor'
                'number' => 9,
                'adult_number' => '1',
                'children_number' => 0,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 11, // 'Madalin'
                'number' => 11,
                'adult_number' => '2',
                'children_number' => 1,
            ],
            [
                'account_id' => Account::first()->id,
                'user_id' => 12, // 'Cosmin'
                'number' => 12,
                'adult_number' => '0',
                'children_number' => 0,
            ],


        ]);
    }
}
