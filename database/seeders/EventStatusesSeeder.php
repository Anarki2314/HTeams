<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_statuses')->insert([
            ['title' => 'Новое'],
            ['title' => 'На проверке'],
            ['title' => 'Регистрация'],
            ['title' => 'Началось'],
            ['title' => 'Завершено'],
            ['title' => 'Итоги'],
        ]);
    }
}
