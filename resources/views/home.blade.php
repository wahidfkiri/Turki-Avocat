@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tableau de Bord</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Tableau de Bord</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_dossiers'] }}</h3>
                            <p>Total Dossiers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <a href="{{ route('dossiers.index') }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['dossiers_contentieux'] }}</h3>
                            <p>Dossiers Contentieux</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <a href="{{ route('dossiers.index', ['filter' => 'contentieux']) }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['dossiers_non_contentieux'] }}</h3>
                            <p>Dossiers Non  Contentieux</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <a href="{{ route('intervenants.index', ['filter' => 'contentieux']) }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                 @if(auth()->user()->hasRole('admin'))
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }}<sup style="font-size: 20px">DT</sup></h3>
                            <p>CA Total (TTC)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <a href="{{ route('factures.index') }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <!-- /.row -->

            <!-- Deuxième ligne de petites boîtes -->
            <div class="row">
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['taches_en_cours'] }}</h3>
                            <p>Tâches en Cours</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <a href="{{ route('tasks.index') }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['factures_impayees'] }}</h3>
                            <p>Factures Impayées</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <a href="{{ route('factures.index', ['statut' => 'non_payé']) }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <!-- ./col -->
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $stats['evenements_semaine'] }}</h3>
                            <p>Événements Cette Semaine</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{ route('agendas.index') }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="@if(auth()->user()->hasRole('admin')) col-lg-3 @else col-lg-4 @endif col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['heures_mois'] }}<sup style="font-size: 20px">h</sup></h3>
                            <p>Heures Facturées (Mois)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('time-sheets.index') }}" class="small-box-footer">
                            Plus d'info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- LINE CHART -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Chiffre d'Affaires des 6 derniers mois</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="caChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <!-- DONUT CHART -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Dossiers par Domaine</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($domaines->count() > 0)
                                <canvas id="domainesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                    <p>Aucun dossier par domaine trouvé</p>
                                </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dossiers Récents</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            @if($dossiers_recents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>N° Dossier</th>
                                            <th>Nom</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dossiers_recents as $dossier)
                                        <tr>
                                            <td>{{ $dossier->numero_dossier }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($dossier->nom_dossier, 25) }}</td>
                                            <td>{{ $dossier->date_entree->format('d/m/Y') }}</td>
                                            <td>
                                                @if($dossier->contentieux)
                                                    <span class="badge bg-warning">Contentieux</span>
                                                @else
                                                    <span class="badge bg-info">Conseil</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p>Aucun dossier récent</p>
                            </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                        @if($dossiers_recents->count() > 0)
                        <div class="card-footer text-center">
                            <a href="{{ route('dossiers.index') }}" class="uppercase">Voir tous les dossiers</a>
                        </div>
                        @endif
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Événements à Venir</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            @if($evenements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Dossier</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($evenements as $event)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::limit($event->titre, 20) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $event->couleur }}; color: white;">
                                                    {{ ucfirst($event->categorie) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($event->dossier)
                                                    {{ $event->dossier->numero_dossier }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <p>Aucun événement à venir</p>
                            </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                        @if($evenements->count() > 0)
                        <div class="card-footer text-center">
                            <a href="{{ route('agendas.index') }}" class="uppercase">Voir l'agenda complet</a>
                        </div>
                        @endif
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
 <script src="{{ asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/dist/js/pages/dashboard3.js')}}"></script>
<!-- AdminLTE App -->

<script>
$(function () {
    'use strict'

    // Chart CA par mois
    var caChartCanvas = $('#caChart').get(0).getContext('2d')
    var caChartData = {
        labels: @json($ca_mois['labels']),
        datasets: [
            {
                label: 'Chiffre d\'Affaires (DT)',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: @json($ca_mois['data'])
            }
        ]
    }

    var caChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    display: false
                },
                ticks: {
                    callback: function(value) {
                        return value + ' DT'
                    }
                }
            }
        }
    }

    var caChart = new Chart(caChartCanvas, {
        type: 'line',
        data: caChartData,
        options: caChartOptions
    })

    // Chart domaines - seulement si des données existent
    @if($domaines->count() > 0)
    var domainesChartCanvas = $('#domainesChart').get(0).getContext('2d')
    var domainesChartData = {
        labels: @json($domaines->pluck('nom')),
        datasets: [
            {
                data: @json($domaines->pluck('dossiers_count')),
                backgroundColor: @json($domaines->pluck('color')),
            }
        ]
    }

    var domainesChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }

    var domainesChart = new Chart(domainesChartCanvas, {
        type: 'doughnut',
        data: domainesChartData,
        options: domainesChartOptions
    })
    @endif

    // Activer les fonctionnalités AdminLTE
    $('[data-card-widget="collapse"]').click(function() {
        var card = $(this).closest('.card');
        card.toggleClass('collapsed-card');
    });

    $('[data-card-widget="remove"]').click(function() {
        var card = $(this).closest('.card');
        card.fadeOut();
    });
})
</script>
@endsection