<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AuraAcademicCalendar extends Component
{
    public $months;
    public $calendar;

    public function render()
    {
        return view('livewire.aura-academic-calendar', [
            'year' => $this->calendar['year'],
            'month' => $this->calendar['month']
        ]);
    }

    public function mount()
    {
        $this->months = [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
        ];

        $this->calendar = [
            'year' => date('Y'),
            'month' => date('n') - 1,
            'array' => []
        ];

        $this->generateCalendar(date('m'), date('Y'));
    }

    public function generateCalendar($month, $year)
    {
        $date = $year.'-'.$month.'-01';

        $monthArray = [];
        while (date('m', strtotime($date)) == $month) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $day = ['', $i];    
                if ($i == date('w', strtotime($date)) && date('m', strtotime($date)) == $month) {
                    $day[0] = date('d', strtotime($date));
    
                    $date = date('Y-m-d', strtotime("+1 days",strtotime($date)));
                }
                array_push($week, $day);
            }
            array_push($monthArray, $week);
        }
        $this->calendar['array'] = $monthArray;
    }

    public function closePopup()
    {
        $this->emitUp('toggleCalendarPopup');
    }

    public function changeMonth($direction)
    {
        if ($direction  == 'prev') {
            $this->calendar['month'] -= 1;
            if ($this->calendar['month'] < 0) {
                $this->calendar['year'] -= 1;
                $this->calendar['month'] = 11;
            }
            
        } else {
            $this->calendar['month'] += 1;

            if ($this->calendar['month'] > 11) {
                $this->calendar['year'] += 1;
                $this->calendar['month'] = 0;
            }
        }
        $this->generateCalendar($this->calendar['month'] + 1, $this->calendar['year']);
    }
}
