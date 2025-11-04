<?php

namespace App\View\Components\Dossier;

use Illuminate\View\Component;

class TabCreate extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dossiers;
    public function __construct($dossiers)
    {
       $this->dossiers = $dossiers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.tab-create');
    }
}
