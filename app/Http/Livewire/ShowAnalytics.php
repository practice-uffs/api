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
        $tuples = Analytics::all();
        $groupedBy = [];
        foreach ($tuples as $tuple) {
            $newTuple = [
                'user_id' => $tuple->user_id,
                'id' => $tuple->id,
                'action' => $tuple->action,
                'key' => $tuple->action,
                'value' => $tuple->value, 
                'rate' => $tuple->rate
            ];
            array_push($groupedBy, $newTuple);
        }
        
        $this->analytics = $groupedBy;
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

        

        if ($group_by == 'none'){
            if ($action == "all_feedback"){
                $query = Analytics::orderBy($order_by)->get();
            } else {
                $query = Analytics::orderBy($order_by)->where('action','=',$action)->get();
            }
        } else {
            if ($action == "all_feedback"){
                $groupedQuery = Analytics::all()->groupBy($group_by);
                $query = $this->reorganizeGroupBy($groupedQuery, $action, $order_by, $group_by);
            } else {
                $groupedQuery = Analytics::all()->where('action','=',$action)->groupBy($group_by);
                $query = $this->reorganizeGroupBy($groupedQuery, $action, $order_by, $group_by);
            }
        }
        
        $this->analytics = $query;
    }

    public function reorganizeGroupBy($query, $action, $order_by, $group_by){
        $groupedBy = [];
        $newTuple = [];

        foreach ($query as $key => $tuples) {
            if ($group_by == "user_id") {
                $newTuple = [
                    'user_id' => $key,
                    'id' => '---',
                    'action' => $action,
                    'key' => '---', 
                ];
            }

            if ($group_by == "key") {
                $newTuple["user_id"] = "---";
                $newTuple["id"] = "---";
                $newTuple["action"] = $action;
                $newTuple["key"] = $key;
                $newTuple["value"] = "---";
            }

            $likes = 0;
            $dislikes = 0;
            $values = [];
            foreach ($tuples as $tuple) {
                array_push($values, ["value" => $tuple["value"], "rate"=>$tuple["rate"]]);
                if ( $tuple["rate"] == 1 ) {
                    $likes += 1;
                } 
                if ( $tuple["rate"] == 0) {
                    $dislikes += 1;
                } 
            }
            $newTuple["value"] = $values;
            $newTuple["rate"]["likes"] = $likes;
            $newTuple["rate"]["dislikes"] = $dislikes;
            
            array_push($groupedBy, $newTuple);
        }
        return $groupedBy;
    }   

}
