<?php

namespace App\View\Components\Dossier;

use Illuminate\View\Component;

class TabEdit extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dossier, $dossiers;
    public function __construct($dossiers, $dossier)
    {
        $this->dossiers = $dossiers;
        $this->dossier = $dossier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.tab-edit');
    }
}
