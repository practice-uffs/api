<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ac = new \CCUFFS\Scrap\AcademicCalendarUFFS();
        $calendars = $ac->fetchCalendars();

        foreach ($calendars as $calendar) {
            \App\Models\AcademicCalendar::create([
                'title' => $calendar['title'],
                'url' => $calendar['url'],
                'data' => $calendar['data']
            ]);
        }
    }
}
