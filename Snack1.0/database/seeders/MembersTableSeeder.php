<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param=[
            'name'=>'Taisei Yamaguchi',
            'image'=>'',
            'deletion'=>0,
        ];
        DB::table('members')->insert($param);

        $param=[
            'name'=>'Warabi',
            'mail'=>'warabi.com',
            'pass'=>'warabi',
            'image'=>'',
            'deletion'=>0,
        ];
        DB::table('members')->insert($param);
    }
}
