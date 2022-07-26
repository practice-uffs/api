<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AuraAcademicCalendar extends Component
{
    public function render()
    {
        return view('livewire.aura-academic-calendar');
    }

    public function closePopup()
    {
        $this->emitUp('toggleCalendarPopup');
    }

    public function changeMonth($direction)
    {
        //
    }
}
