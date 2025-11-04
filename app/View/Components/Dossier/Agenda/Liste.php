<?php

namespace App\View\Components\Dossier\Agenda;

use Illuminate\View\Component;

class Liste extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dossier;
    public $users;
    public $intervenants;
    public $categories;
    public $types;  
    public function __construct($dossier, $users, $intervenants, $categories, $types)
    {
        $this->dossier = $dossier;
        $this->users = $users;
        $this->intervenants = $intervenants;
        $this->categories = $categories;
        $this->types = $types;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
            return view('components.dossier.agenda.list', [
                'dossier' => $this->dossier,
                'users' => \App\Models\User::role(['avocat', 'secrétaire'])->get(),
                'intervenants' => \App\Models\Intervenant::all(),
                'categories' => \App\Models\CategorieAgenda::all(),
                'types' => \App\Models\TypeAgenda::all(),
            ]);
        }
}
