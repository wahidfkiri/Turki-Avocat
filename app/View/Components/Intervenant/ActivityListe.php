<?php

namespace App\View\Components\Intervenant;

use Illuminate\View\Component;

class ActivityListe extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $intervenant;
    public function __construct($intervenant)
    {
        $this->intervenant = $intervenant;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.intervenants.activity-liste');
    }
}
