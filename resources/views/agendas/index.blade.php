@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Agenda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Agenda</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Boutons d'action en haut à droite -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end">
                        <div class="btn-group">
                            <button type="button" id="btn_today" class="btn btn-info btn-sm">
                                Aujourd'hui
                            </button>
                            <button type="button" id="btn_reset_filters" class="btn btn-secondary btn-sm">
                                Réinitialiser
                            </button>
                            @if(auth()->user()->hasPermission('create_agendas'))
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createEventModal">
                                    <i class="fas fa-plus"></i> Créer un événement
                                </button>
                            @endif
                            <button type="button" class="btn btn-outline-primary btn-sm" id="toggleFiltersBtn">
                                <i class="fas fa-filter"></i> Filtres
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Filtres - Caché par défaut -->
                <div class="col-md-3 d-none" id="filtersSidebar">
                    <!-- Filtres -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filtres</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" id="closeFiltersBtn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtre par catégorie -->
                            <div class="form-group">
                                <label>Catégories</label>
                                <a href="#" style="float:right;" class="text-primary" data-toggle="modal" data-target="#createCategorieModal"><i class="fa fa-plus text-primary"></i> Ajouter </a>
                                @foreach($categories as $categorie)
                                <div class="custom-control custom-checkbox category-item">
                                <span class="legend-color" style="background-color: {{$categorie->couleur}}; margin-right:30px;"></span>
                                    <input class="custom-control-input" type="checkbox" id="filter_{{$categorie->nom}}" checked data-category="{{$categorie->nom}}">
                                    <label for="filter_{{$categorie->nom}}" class="custom-control-label">{{$categorie->nom}}</label>
                                    <!-- Bouton Supprimer -->
    <a href="#" class="delete-category" data-id="{{$categorie->id}}" data-name="{{$categorie->nom}}">
        <i class="fa fa-trash text-danger" style="float:right;" tooltip="Supprimer"></i>
    </a>
    
    <!-- Bouton Modifier -->
    <a href="#" class="edit-category" data-id="{{$categorie->id}}" data-name="{{$categorie->nom}}" data-color="{{$categorie->couleur}}">
        <i class="fa fa-edit text-info" style="float:right; margin-right: 10px;" tooltip="Modifier"></i>
    </a>
                                </div>
                                @endforeach
                                <!-- <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_audience" checked data-category="audience">
                                    <label for="filter_audience" class="custom-control-label">Audience</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_delai" checked data-category="delai">
                                    <label for="filter_delai" class="custom-control-label">Délai</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_tache" checked data-category="tache">
                                    <label for="filter_tache" class="custom-control-label">Tâche</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_autre" checked data-category="autre">
                                    <label for="filter_autre" class="custom-control-label">Autre</label>
                                </div> -->
                            </div>

                            <!-- Filtre par utilisateur -->
                            <div class="form-group">
                                <label for="filter_utilisateur">Utilisateur</label>
                                <select class="form-control" id="filter_utilisateur">
                                    <option value="">Tous les utilisateurs</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par dossier -->
                            <div class="form-group">
                                <label for="filter_dossier">Dossier</label>
                                <select class="form-control" id="filter_dossier">
                                    <option value="">Tous les dossiers</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Calendar - Prend toute la largeur -->
                <div class="col-12 w-100" id="calendarContainer">
                    <!-- Calendar -->
                    <div class="card card-primary w-100">
                        <div class="card-body p-0 w-100">
                           <div id="calendar" style="height: auto; min-height: 700px; max-height: 80vh; overflow-y: auto;width:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Modal pour les détails de l'événement -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Détails de l'événement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Les détails seront chargés ici -->
            </div>
            <div class="modal-footer">
                @if(auth()->user()->hasPermission('edit_agendas'))
                    <button type="button" class="btn btn-primary" id="btnEditEvent">Modifier</button>
                @endif
                @if(auth()->user()->hasPermission('delete_agendas'))
                    <button type="button" class="btn btn-danger" id="btnDeleteEvent">Supprimer</button>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un événement -->
<div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Nouvel événement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createEventForm"  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titre">Titre *</label>
                                <input type="text" class="form-control" id="titre" name="titre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categorie">Catégorie *</label>
                                <select class="form-control" id="categorie" name="categorie" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{$categorie->nom}}">{{$categorie->nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_debut">Date début *</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_fin">Date fin</label>
                                <input type="date" class="form-control" id="date_fin" name="date_fin">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="heure_debut">Heure début</label>
                                <input type="time" class="form-control" id="heure_debut" name="heure_debut">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="heure_fin">Heure fin</label>
                                <input type="time" class="form-control" id="heure_fin" name="heure_fin">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="all_day" name="all_day" value="1">
                            <label class="custom-control-label" for="all_day">Journée entière</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        @if(auth()->user()->hasRole('admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="utilisateur_id">Assigné à</label>
                                <select class="form-control" id="utilisateur_id" name="utilisateur_id">
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else 
                        <input type="hidden" name="utilisateur_id" value="{{auth()->user()->id}}">
                        @endif
                        <div class="@if(auth()->user()->hasRole('admin')) col-md-6 @else col-md-12 @endif">
                            <div class="form-group">
                                <label for="dossier_id">Dossier</label>
                                <select class="form-control" id="dossier_id" name="dossier_id">
                                    <option value="">Sélectionnez un dossier</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="intervenant_id">Intervenant</label>
                        <select class="form-control" id="intervenant_id" name="intervenant_id">
                            <option value="">Sélectionnez un intervenant</option>
                            @foreach($intervenants as $intervenant)
                                <option value="{{ $intervenant->id }}">{{ $intervenant->identite_fr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="file">Pièce Jointe</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnCreateEvent">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour créer une catégorie -->
<div class="modal fade" id="createCategorieModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Nouvel catégorie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createCategorieForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="categorie">Nom Catégorie *</label>
                                <input type="text" class="form-control" id="categorie_name" name="categorie" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="date_debut">Couleur *</label>
                                <input type="color" class="form-control" id="color" name="color" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnCreateCategorie">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la catégorie</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_category_id" name="id">
                    <div class="form-group">
                        <label for="edit_category_name">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="edit_category_name" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_category_color">Couleur</label>
                        <input type="color" class="form-control" id="edit_category_color" name="couleur" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveCategoryChanges">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation suppression -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie "<span id="category_name_to_delete"></span>" ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Supprimer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour modifier un événement -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Modifier l'événement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEventForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_event_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_titre">Titre *</label>
                                <input type="text" class="form-control" id="edit_titre" name="titre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_categorie">Catégorie *</label>
                                <select class="form-control" id="edit_categorie" name="categorie" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{$categorie->nom}}">{{$categorie->nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_date_debut">Date début *</label>
                                <input type="date" class="form-control" id="edit_date_debut" name="date_debut" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_date_fin">Date fin</label>
                                <input type="date" class="form-control" id="edit_date_fin" name="date_fin">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_heure_debut">Heure début</label>
                                <input type="time" class="form-control" id="edit_heure_debut" name="heure_debut">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_heure_fin">Heure fin</label>
                                <input type="time" class="form-control" id="edit_heure_fin" name="heure_fin">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_all_day" name="all_day" value="1">
                            <label class="custom-control-label" for="edit_all_day">Journée entière</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        @if(auth()->user()->hasRole('admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_utilisateur_id">Assigné à </label>
                                <select class="form-control" id="edit_utilisateur_id" name="utilisateur_id">
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else 
                        <input type="hidden" name="utilisateur_id" value="{{auth()->user()->id}}">
                        @endif
                        <div class="@if(auth()->user()->hasRole('admin')) col-md-6 @else col-md-12 @endif">
                            <div class="form-group">
                                <label for="edit_dossier_id">Dossier</label>
                                <select class="form-control" id="edit_dossier_id" name="dossier_id">
                                    <option value="">Sélectionnez un dossier</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_intervenant_id">Intervenant</label>
                        <select class="form-control" id="edit_intervenant_id" name="intervenant_id">
                            <option value="">Sélectionnez un intervenant</option>
                            @foreach($intervenants as $intervenant)
                                <option value="{{ $intervenant->id }}">{{ $intervenant->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_couleur">Couleur</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateEvent">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEventModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.</p>
                <p><strong id="deleteEventTitle"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<<style>
/* Styles pour le conteneur principal */
.content-wrapper {
    overflow-x: hidden;
}

/* Styles pour les filtres et le calendrier */
#filtersSidebar {
    transition: all 0.3s ease-in-out;
}

#calendarContainer {
    transition: all 0.3s ease-in-out;
}

/* Assurer que le calendrier prend toute la largeur */
#calendar {
    width: 100% !important;
}

.card-body.p-0.w-100 {
    width: 100% !important;
}

/* Correction pour le responsive */
@media (max-width: 768px) {
    #filtersSidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        height: 100vh;
        z-index: 1050;
        background: white;
        overflow-y: auto;
        transition: left 0.3s ease-in-out;
    }
    
    #filtersSidebar.d-none {
        left: -100%;
    }
    
    #filtersSidebar:not(.d-none) {
        left: 0;
    }
    
    /* Sur mobile, le calendrier prend toujours 100% */
    #calendarContainer {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}

/* Overlay pour mobile */
.filters-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1049;
}

@media (max-width: 768px) {
    .filters-overlay.active {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var currentEventId = null;
    var currentEventTitle = null;
    var filtersVisible = false;
    var calendar;


    // Set today's date as default for new events
    $('#date_debut').val(new Date().toISOString().split('T')[0]);

    // Initialize Calendar
   // Initialize Calendar
function initializeCalendar() {
    calendar = new FullCalendar.Calendar(calendarEl, {
        
  initialView: 'timeGridDay',
  scrollTime: '08:00:00', // scrolls to 8 AM by default
  slotMinTime: '06:00:00', // earliest time visible
  defaultTimedEventDuration: '01:00:00', // default event length (optional)

        locale: 'fr',
        timeZone: 'local',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        views: {
            dayGridMonth: { 
                buttonText: 'Mois',
                dayMaxEventRows: 3,
                dayMaxEvents: true
            },
            timeGridWeek: { buttonText: 'Semaine' },
            timeGridDay: { buttonText: 'Jour' },
            listWeek: { buttonText: 'Liste' ,
                listDayFormat: { 
                        month: 'long', 
                        day: 'numeric', 
                        year: 'numeric',
                        weekday: 'short'
                    },
            },
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste',
        },
        navLinks: true,
        editable: false,
        selectable: true,
        nowIndicator: true,
        dayMaxEvents: true,
        height: 'auto',
        contentHeight: 'auto',
        events: {
            url: '{{ route("agendas.data") }}',
            method: 'GET',
            extraParams: function() {
                return {
                    categories: getSelectedCategories(),
                    utilisateur_id: $('#filter_utilisateur').val(),
                    dossier_id: $('#filter_dossier').val()
                };
            },
            failure: function() {
                showAlert('Erreur', 'Erreur lors du chargement des événements', 'error');
            }
        },
        eventClick: function(info) {
            currentEventId = info.event.id;
            currentEventTitle = info.event.title;
            showEventDetails(info.event);
        },
        dateClick: function(info) {
            @if(auth()->user()->hasPermission('create_agendas'))
                $('#date_debut').val(info.dateStr);
                $('#createEventModal').modal('show');
            @endif
        },
        eventDidMount: function(info) {
            // Apply custom colors and tooltips
            var event = info.event;
            
            // Tooltip avec les détails de l'événement
            var tooltipContent = event.title;
            if (event.extendedProps.description) {
                tooltipContent += '<br>' + event.extendedProps.description;
            }
            if (event.extendedProps.dossier) {
                tooltipContent += '<br>Dossier: ' + event.extendedProps.dossier;
            }
            if (event.extendedProps.intervenant) {
                tooltipContent += '<br>Intervenant: ' + event.extendedProps.intervenant;
            }
            
            $(info.el).tooltip({
                title: tooltipContent,
                html: true,
                placement: 'top'
            });
            
            // Ensure colors are applied correctly
            if (event.backgroundColor) {
                info.el.style.backgroundColor = event.backgroundColor;
            }
            if (event.textColor) {
                info.el.style.color = event.textColor;
            }
        },
        windowResize: function(view) {
            calendar.updateSize();
        },
        eventContent: function(arg) {
            // Custom event content to ensure colors display properly
            var title = arg.event.title;
            var timeText = '';
            
            if (!arg.event.allDay && arg.event.start) {
                var startTime = arg.event.start.toLocaleTimeString('fr-FR', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                timeText = startTime + ' ';
            }
            
            return {
                html: `<div class="fc-event-main-frame">
                         <div class="fc-event-title">${title}</div>
                       </div>`
            };
        }
    });

    calendar.render();
}

    // Initialiser le calendrier
    initializeCalendar();

    // Fonction pour afficher/masquer les filtres
    $('#toggleFiltersBtn').click(function() {
        if (filtersVisible) {
            closeFilters();
        } else {
            openFilters();
        }
    });

    // Fermer les filtres
    $('#closeFiltersBtn').click(function() {
        closeFilters();
    });

    function openFilters() {
        $('#filtersSidebar').removeClass('d-none');
        $('#calendarContainer').removeClass('col-12').addClass('col-md-9');
        filtersVisible = true;
        
        // Redimensionner le calendrier après un court délai pour permettre le rendu CSS
        setTimeout(function() {
            if (calendar) {
                calendar.updateSize();
            }
        }, 100);
    }

    function closeFilters() {
        $('#filtersSidebar').addClass('d-none');
        $('#calendarContainer').removeClass('col-md-9').addClass('col-12');
        filtersVisible = false;
        
        // Redimensionner le calendrier après un court délai pour permettre le rendu CSS
        setTimeout(function() {
            if (calendar) {
                calendar.updateSize();
            }
        }, 100);
    }

    // Fonction pour afficher les alertes
    function showAlert(title, message, type = 'info') {
        if (type === 'error') {
            alert('❌ ' + title + ': ' + message);
        } else if (type === 'success') {
            alert('✅ ' + message);
        } else {
            alert('ℹ️ ' + message);
        }
    }

    // Fonction pour obtenir les catégories sélectionnées
    function getSelectedCategories() {
        var categories = [];
        $('input[data-category]:checked').each(function() {
            categories.push($(this).data('category'));
        });
        return categories.join(',');
    }

    // Fonction pour afficher les détails de l'événement
    function showEventDetails(event) {
        var details = `
            <div class="event-details">
                <h4>${event.title}</h4>
                <p><strong>Catégorie:</strong> ${getCategoryLabel(event.extendedProps.categorie)}</p>
                ${event.extendedProps.description ? `<p><strong>Description:</strong> ${event.extendedProps.description}</p>` : ''}
                <p><strong>Date:</strong> ${formatEventDate(event)}</p>
                ${event.extendedProps.dossier ? `<p><strong>Dossier:</strong> ${event.extendedProps.dossier}</p>` : ''}
                ${event.extendedProps.intervenant ? `<p><strong>Intervenant:</strong> ${event.extendedProps.intervenant}</p>` : ''}
                ${event.extendedProps.utilisateur ? `<p><strong>Assigné à:</strong> ${event.extendedProps.utilisateur}</p>` : ''}
                ${event.extendedProps.file_name ? `<p><strong><a href="{{url('agendas/download')}}/${event.id}"><i class="fa fa-download"></i> Télécharger</a></strong> ${event.extendedProps.file_name}</p>` : ''}
            </div>
        `;
        
        $('#eventModalBody').html(details);
        $('#eventModal').modal('show');
    }

    // Fonction pour formater la date de l'événement
    function formatEventDate(event) {
        if (event.allDay) {
            return event.start.toLocaleDateString('fr-FR');
        } else {
            var start = event.start.toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            if (event.end) {
                var end = event.end.toLocaleString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                return `${start} - ${end}`;
            }
            return start;
        }
    }

    // Fonction pour obtenir le label de la catégorie
    function getCategoryLabel(categorieName) {
        var labels = {!! json_encode($categories->pluck('nom','nom')) !!};
        return labels[categorieName] || categorieName;
    }

    // Gestion de la case "Journée entière"
    $('#all_day').change(function() {
        if ($(this).is(':checked')) {
            $('#heure_debut, #heure_fin').val('').prop('disabled', true);
        } else {
            $('#heure_debut, #heure_fin').prop('disabled', false);
        }
    });

    // Création d'événement
    $('#createEventForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("agendas.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createEventModal').modal('hide');
                calendar.refetchEvents();
                $('#createEventForm')[0].reset();
                $('#date_debut').val(new Date().toISOString().split('T')[0]);
                $('#heure_debut, #heure_fin').prop('disabled', false);
                showAlert('Succès', 'Événement créé avec succès', 'success');
            },
            error: function(xhr) {
                let errorMessage = 'Erreur de validation:\n';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += '• ' + value[0] + '\n';
                    });
                } else {
                    errorMessage += 'Une erreur est survenue.';
                }
                showAlert('Erreur', errorMessage, 'error');
            }
        });
    });

    // Modification d'événement
    $('#btnEditEvent').click(function() {
        $('#eventModal').modal('hide');
        loadEventForEdit(currentEventId);
    });

    function loadEventForEdit(eventId) {
        $.ajax({
            url: '/agendas/' + eventId + '/edit',
            type: 'GET',
            success: function(response) {
                // Pré-remplir le formulaire de modification
                $('#edit_event_id').val(response.id);
                $('#editEventForm input[name="titre"]').val(response.titre);
                $('#editEventForm select[name="categorie"]').val(response.categorie);
                $('#editEventForm input[name="date_debut"]').val(response.date_debut);
                $('#editEventForm input[name="date_fin"]').val(response.date_fin);
                $('#editEventForm input[name="heure_debut"]').val(response.heure_debut);
                $('#editEventForm input[name="heure_fin"]').val(response.heure_fin);
                $('#editEventForm input[name="all_day"]').prop('checked', response.all_day);
                $('#editEventForm textarea[name="description"]').val(response.description);
                $('#editEventForm select[name="utilisateur_id"]').val(response.utilisateur_id).trigger('change');
                $('#editEventForm select[name="dossier_id"]').val(response.dossier_id).trigger('change');
                $('#editEventForm select[name="intervenant_id"]').val(response.intervenant_id).trigger('change');
                $('#editEventForm input[name="couleur"]').val(response.couleur);
                $('#editEventForm input[name="file"]').val(response.file);

                if (response.all_day) {
                    $('#editEventForm input[name="heure_debut"], #editEventForm input[name="heure_fin"]').prop('disabled', true);
                }

                $('#editEventModal').modal('show');
            },
            error: function() {
                showAlert('Erreur', 'Erreur lors du chargement des données', 'error');
            }
        });
    }

    $('#editEventForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: '/agendas/' + $('#edit_event_id').val(),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editEventModal').modal('hide');
                calendar.refetchEvents();
                showAlert('Succès', 'Événement mis à jour avec succès', 'success');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = 'Erreur de validation:\n';
                $.each(errors, function(key, value) {
                    errorMessage += '• ' + value[0] + '\n';
                });
                showAlert('Erreur', errorMessage, 'error');
            }
        });
    });

    // Suppression d'événement
    $('#btnDeleteEvent').click(function() {
        $('#eventModal').modal('hide');
        $('#deleteEventTitle').text(currentEventTitle);
        $('#deleteEventModal').modal('show');
    });

    $('#btnConfirmDelete').click(function() {
        $.ajax({
            url: '/agendas/' + currentEventId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                $('#deleteEventModal').modal('hide');
                calendar.refetchEvents();
                showAlert('Succès', 'Événement supprimé avec succès', 'success');
            },
            error: function() {
                showAlert('Erreur', 'Une erreur est survenue lors de la suppression', 'error');
            }
        });
    });

    // Événements des filtres
    $('input[data-category]').change(function() {
        calendar.refetchEvents();
    });

    $('#filter_utilisateur, #filter_dossier').change(function() {
        calendar.refetchEvents();
    });

    // Bouton aujourd'hui
    $('#btn_today').click(function() {
        calendar.today();
    });

    // Bouton réinitialiser
    $('#btn_reset_filters').click(function() {
        $('input[data-category]').prop('checked', true);
        $('#filter_utilisateur').val('').trigger('change');
        $('#filter_dossier').val('').trigger('change');
        calendar.refetchEvents();
    });

    // Redimensionner le calendrier quand la fenêtre change
    $(window).resize(function() {
        if (calendar) {
            setTimeout(function() {
                calendar.updateSize();
            }, 150);
        }
    });

    // AJAX function to create new category
$('#createCategorieForm').submit(function(e) {
    e.preventDefault();

    var formData = {
        nom: $('#categorie_name').val(),
        couleur: $('#color').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("agenda-categories.store") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            $('#createCategorieModal').modal('hide');
            $('#createCategorieForm')[0].reset();
            
            // Add the new category to the filter list
            addCategoryToFilter(response.category);
            
            // Add the new category to event creation form
            addCategoryToEventForm(response.category);
            
            showAlert('Succès', 'Catégorie créée avec succès', 'success');
        },
        error: function(xhr) {
            let errorMessage = 'Erreur de validation:\n';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function(key, value) {
                    errorMessage += '• ' + value[0] + '\n';
                });
            } else {
                errorMessage += 'Une erreur est survenue.';
            }
            showAlert('Erreur', errorMessage, 'error');
        }
    });
});

// Function to add new category to filter list
function addCategoryToFilter(category) {
    var categoryId = 'filter_' + category.id;
    var checkboxHtml = `
        <div class="custom-control custom-checkbox">
            <span class="legend-color" style="background-color: ${category.couleur}; margin-right:30px;"></span>
            <input class="custom-control-input" type="checkbox" id="${categoryId}" checked data-category="${category.id}">
            <label for="${categoryId}" class="custom-control-label">${category.nom}</label>
        </div>
    `;
    
    // Append to the filter categories container
    $('.form-group:has(label:contains("Catégories"))').append(checkboxHtml);
    
    // Add event listener for the new checkbox
    $('#' + categoryId).change(function() {
        calendar.refetchEvents();
    });
}

// Function to add new category to event creation form
function addCategoryToEventForm(category) {
    var optionHtml = `<option value="${category.id}">${category.nom}</option>`;
    
    // Add to create event form
    $('#categorie').append(optionHtml);
    
    // Add to edit event form
    $('#edit_categorie').append(optionHtml);
}

// Function to load categories dynamically (optional - if you want to refresh categories)
function loadCategories() {
    $.ajax({
        url: '{{ route("agenda-categories.api") }}',
        type: 'GET',
        success: function(response) {
            // Clear existing categories from forms
            $('#categorie').find('option:not(:first)').remove();
            $('#edit_categorie').find('option:not(:first)').remove();
            
            // Clear filter categories (keep the "Ajouter" link)
            $('.form-group:has(label:contains("Catégories")) .custom-control.custom-checkbox').remove();
            
            // Add all categories
            response.forEach(function(category) {
                addCategoryToFilter(category);
                addCategoryToEventForm(category);
            });
        },
        error: function() {
            showAlert('Erreur', 'Erreur lors du chargement des catégories', 'error');
        }
    });
}

});
</script>
<script>
$(document).ready(function() {
    let categoryToDelete = null;

    // Gestion de la modification
    $('.edit-category').on('click', function(e) {
        e.preventDefault();
        
        const categoryId = $(this).data('id');
        const categoryName = $(this).data('name');
        const categoryColor = $(this).data('color');
        
        $('#edit_category_id').val(categoryId);
        $('#edit_category_name').val(categoryName);
        $('#edit_category_color').val(categoryColor);
        
        $('#editCategoryModal').modal('show');
    });

    // Sauvegarde des modifications
    $('#saveCategoryChanges').on('click', function() {
        const categoryId = $('#edit_category_id').val();
        const formData = {
            nom: $('#edit_category_name').val(),
            couleur: $('#edit_category_color').val(),
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };

        $.ajax({
            url: '/agendas/categories/' + categoryId,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'affichage
                    const categoryElement = $('#category-' + categoryId);
                    categoryElement.find('.custom-control-label').text(response.categorie.nom);
                    categoryElement.find('.legend-color').css('background-color', response.categorie.couleur);
                    categoryElement.find('.edit-category').data('name', response.categorie.nom);
                    categoryElement.find('.edit-category').data('color', response.categorie.couleur);
                    
                    $('#editCategoryModal').modal('hide');
                    showAlert('success', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response?.message || 'Erreur lors de la modification');
            }
        });
    });

    // Gestion de la suppression
    $('.delete-category').on('click', function(e) {
        e.preventDefault();
        
        categoryToDelete = $(this).data('id');
        const categoryName = $(this).data('name');
        
        $('#category_name_to_delete').text(categoryName);
        $('#deleteCategoryModal').modal('show');
    });

    // Confirmation de suppression
    $('#confirmDeleteCategory').on('click', function() {
        if (!categoryToDelete) return;

        $.ajax({
            url: '/agendas/categories/' + categoryToDelete,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    // Supprimer l'élément du DOM
                    $('#category-' + categoryToDelete).remove();
                    $('#deleteCategoryModal').modal('hide');
                    showAlert('success', response.message);
                    
                    // Recharger la page si plus de catégories
                    if ($('.category-item').length === 0) {
                        location.reload();
                    }
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                $('#deleteCategoryModal').modal('hide');
                showAlert('error', response?.message || 'Erreur lors de la suppression');
            }
        });
    });

    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        // Afficher l'alerte en haut de la page
        $('.content-wrapper').prepend(alertHtml);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
        setTimeout(function() {
    window.location.reload();
}, 1500);
    }
});
</script>
@endsection