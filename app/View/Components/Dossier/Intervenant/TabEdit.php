<?php

namespace App\View\Components\Dossier\Intervenant;

use Illuminate\View\Component;

class TabEdit extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dossier;
    public $intervenants;
    public function __construct($dossier, $intervenants)
    {
        $this->dossier = $dossier;
        $this->intervenants = $intervenants;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.intervenant.tab-edit');
    }
}
