<?php

namespace App\Http\Livewire;
use App\Models\Analytics;

use Livewire\Component;

class ShowAnalytics extends Component
{
    public function render()
    {
        $analytics = Analytics::all();
        return view('livewire.show-analytics', ['analytics' => $analytics]);
    }
}
