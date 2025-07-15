<?php

namespace App\View\Components;

use App\Helpers\MyHelper;
use Illuminate\View\Component;

class MonthList extends Component
{
    public $idName;
    public $name;
    // public $countries;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($idName , $name)
    {
        //
        $this->idName = $idName;
        // $this->countries = $countries;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.month-list' , ['data' => MyHelper::months()]);
    }
}
