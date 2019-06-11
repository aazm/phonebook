<?php

use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(range(0, 99))->map(function ($i){
            factory(\App\Record::class)->create();
        });
    }
}
