<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users  =   [
            [
                'officer_number'    =>  '7860609688',
                'fullname'          =>  'Administrator',
                'password'          =>  Hash::make('7860609688'),
                'isAdmin'           =>  TRUE,
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'officer_number'    =>  '8840130301',
                'fullname'          =>  'Petugas Parkir 1',
                'password'          =>  Hash::make('8840130301'),
                'isAdmin'           =>  FALSE,
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'officer_number'    =>  '5397833191',
                'fullname'          =>  'Petugas Parkir 2',
                'password'          =>  Hash::make('5397833191'),
                'isAdmin'           =>  FALSE,
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];

        foreach ($users as $user) {
            $arr    =   User::firstOrCreate($user);
        }
    }
}
