<div class="tab-pane fade" id="agenda" role="tabpanel" aria-labelledby="agenda-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-calendar-alt"></i> Agenda</h5>

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
                                <button type="button" class="btn btn-outline-primary btn-sm" id="toggleFiltersBtn" data-toggle="modal" data-target="#filtersModal">
                                    <i class="fas fa-filter"></i> Filtres
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Calendar - Prend toute la largeur -->
                    <div class="col-12" id="calendarContainer">
                        <!-- Calendar -->
                        <div class="card card-primary">
                            <div class="card-body p-0">
                                <div id="calendar" style="height: auto; min-height: 700px; max-height: 80vh; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
</div>

<!-- Modal pour les filtres -->
<div class="modal fade" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersModalLabel">Filtres</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre par catégorie -->
                <div class="form-group">
                    <label>Catégories</label>
                    <a href="" style="float:right;" class="text-primary" data-toggle="modal" data-target="#createCategorieModal">Ajouter </a>
                    @foreach(\App\Models\AgendaCategory::all() as $categorie)
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
                        <option value="{{ $dossier->id }}" selected>{{ $dossier->numero_dossier }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="applyFilters">Appliquer les filtres</button>
            </div>
        </div>
    </div>
</div>

<!-- Les autres modals restent inchangés -->
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
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer une catégorie -->
<div class="modal fade" id="createCategorieModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
            <form id="createEventForm" method="POST" enctype="multipart/form-data">
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
                                    @foreach(\App\Models\AgendaCategory::all() as $categorie)
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
                                <label for="dossier_id">Dossier 11</label>
                                <select class="form-control" id="dossier_id" name="dossier_id">
                                    <option value="{{ $dossier->id }}" selected>{{ $dossier->numero_dossier }}</option>
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
                                    @foreach(\App\Models\AgendaCategory::all() as $categorie)
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
                                    <option value="{{ $dossier->id }}" selected>{{ $dossier->numero_dossier }}</option>
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

<style>
/* Style pour améliorer l'affichage du calendrier en plein écran */
#calendar {
    min-height: 700px;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.legend-color {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    border-radius: 3px;
}

.legend-text {
    font-size: 14px;
}

/* Style pour les boutons en mode responsive */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 5px;
        width: 100%;
    }
}

/* Assurer que le calendrier s'adapte correctement */
.fc .fc-view-harness {
    min-height: 600px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var currentEventId = null;
    var currentEventTitle = null;
    var calendar;

    // Set today's date as default for new events
    $('#date_debut').val(new Date().toISOString().split('T')[0]);

    // Initialize Calendar
    function initializeCalendar() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            scrollTime: '08:00:00', // scrolls to 8 AM by default
            slotMinTime: '06:00:00', // earliest time visible
            defaultTimedEventDuration: '01:00:00', // default event length (optional)
            locale: 'fr',
            timeZone: 'local',
            initialView: 'listAllEvents', // Changed to custom list view
            headerToolbar: false,
            views: {
                dayGridMonth: { 
                    buttonText: 'Mois',
                    dayMaxEventRows: 3,
                    dayMaxEvents: true
                },
                timeGridWeek: { buttonText: 'Semaine' },
                timeGridDay: { buttonText: 'Jour' },
                listWeek: { 
                    buttonText: 'Liste Semaine',
                    visibleRange: function(currentDate) {
                        return {
                            start: currentDate.startOf('week'),
                            end: currentDate.endOf('week')
                        };
                    }
                },
                listAllEvents: {
                    type: 'list',
                    buttonText: 'Tous les Événements',
                    // Show events from 10 years ago to 10 years in the future
                    visibleRange: {
                        start: '2010-01-01',
                        end: '2300-12-31'
                    },
                    // Alternative: Show unlimited events without date range filtering
                    listDayFormat: { 
                        month: 'long', 
                        day: 'numeric', 
                        year: 'numeric',
                        weekday: 'short'
                    },
                    noEventsContent: 'Aucun événement'
                }
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            },
            navLinks: true,
            editable: false,
            selectable: true,
            nowIndicator: true,
            dayMaxEvents: true,
            height: 'auto',
            contentHeight: 'auto',
            events: {
                url: '{{ route("agendas.data.by.dossier", $dossier->id) }}',
                method: 'GET',
                extraParams: function() {
                    return {
                        categories: getSelectedCategories(),
                        utilisateur_id: $('#filter_utilisateur').val(),
                        dossier_id: $('#filter_dossier').val(),
                        // Add parameter to indicate we want all events
                        all_events: calendar?.view?.type === 'listAllEvents' ? 1 : 0
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

    // Appliquer les filtres depuis la modal
    $('#applyFilters').click(function() {
        $('#filtersModal').modal('hide');
        calendar.refetchEvents();
    });

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
                ${event.extendedProps.file_name ? `<p><strong>Fichier:</strong> ${event.extendedProps.file_name}</p>` : ''}
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
    function getCategoryLabel(categorie) {
        var labels = {
            'rdv': 'Rendez-vous',
            'audience': 'Audience',
            'delai': 'Délai',
            'tache': 'Tâche',
            'autre': 'Autre'
        };
        return labels[categorie] || categorie;
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
        // Les filtres s'appliquent quand on clique sur "Appliquer les filtres"
    });

    $('#filter_utilisateur, #filter_dossier').change(function() {
        // Les filtres s'appliquent quand on clique sur "Appliquer les filtres"
    });

    // Bouton aujourd'hui
    $('#btn_today').click(function() {
        calendar.today();
    });

    // Bouton réinitialiser
    $('#btn_reset_filters').click(function() {
        // Reset all filters in the modal
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
            // Les filtres s'appliquent quand on clique sur "Appliquer les filtres"
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