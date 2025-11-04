<?php

namespace App\View\Components\Dossier\Equipe;

use Illuminate\View\Component;

class TabCreate extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $users;
    public function __construct($users)
    {
        $this->users = $users; 
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dossier.equipe.tab-create');
    }
}
