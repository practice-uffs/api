<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class AcademicCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fetchCalendars();
    }

    public function fetchCalendars() {
        $ac = new \CCUFFS\Scrap\AcademicCalendarUFFS();
        $calendars = $ac->fetchCalendars();

        foreach ($calendars as $calendar) {
            $ac = \App\Models\AcademicCalendar::where('title', $calendar['title'])->first();

            if ($ac != null) {
                \App\Models\AcademicCalendar::where('title', $calendar['title'])->update([
                    'url' => $calendar['url'],
                    'data' => $calendar['data']
                ]);
            } else {
                \App\Models\AcademicCalendar::create([
                    'title' => $calendar['title'],
                    'url' => $calendar['url'],
                    'data' => $calendar['data']
                ]);
            }            
        }
    }
}
