<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NombreEnLettre extends Component
{
    public $nombre;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($nombre)
    {
        //
        $this->nombre = $nombre;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.nombre-en-lettre');
    }
}
