<?php

namespace App\Http\Livewire;
use App\Models\Analytics;

use Livewire\Component;

class ShowAnalytics extends Component
{
    public $analytics;
    public $searchParams;

    public function mount()
    {
        $analytics = Analytics::all();
        $this->analytics = $analytics;
        $this->searchParams = array("action" => "all_feedback","order_by"=>"id","group_by"=>"none" );
    }

    public function render()
    {
        return view('livewire.show-analytics');
    }

    public function query( $action, $order_by, $group_by ){
        
        $this->searchParams['action']   = $action;
        $this->searchParams['order_by'] = $order_by;
        $this->searchParams['group_by'] = $group_by;

        if ($action == "all_feedback"){
            $query = Analytics::all()->groupBy($group_by);
            return;
        }

        if ($group_by == 'none'){
            $query = Analytics::orderBy($order_by)->where('action','=',$action)->get();
        } else {
            $query = Analytics::all()->where('action','=',$action)->groupBy($group_by);
        }
        
        $this->analytics = $query;
    }

}
