<?php

namespace App\View\Components\Dossier\Equipe;

use Illuminate\View\Component;

class TabEdit extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $users, $dossier;
    public function __construct($users, $dossier)
    {
        $this->users = $users; 
        $this->dossier = $dossier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.equipe.tab-edit');
    }
}
