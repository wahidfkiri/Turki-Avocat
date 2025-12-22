<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo1.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css?v=3.2.0') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <style>
    .badge {
        font-size: 0.8em;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .dataTables_wrapper {
        padding: 0;
    }
    /* Style pour la pagination */
    .dataTables_paginate .paginate_button {
        margin: 0 2px;
        padding: 6px 12px;
    }
    /* Classe de base pour l'avatar circulaire */
.user-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
}

/* Variantes de taille */
.user-avatar-sm {
    width: 30px;
    height: 30px;
    font-size: 12px;
}

.user-avatar-lg {
    width: 50px;
    height: 50px;
    font-size: 16px;
}

.user-avatar-xl {
    width: 60px;
    height: 60px;
    font-size: 18px;
}

    #calendar {
        height: 600px;
        background-color: white;
    }
    .fc-header-toolbar {
        padding: 10px;
        margin-bottom: 0 !important;
    }
    .fc-toolbar-chunk {
        display: flex;
        align-items: center;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 3px;
        margin-right: 10px;
        display: inline-block;
    }
    .legend-text {
        font-size: 14px;
    }
    .event-details h4 {
        color: #3c8dbc;
        margin-bottom: 15px;
    }
    .event-details p {
        margin-bottom: 8px;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-day-today {
        background-color: #e8f4fd !important;
    }
    input[type="color"] {
        height: 38px;
        padding: 2px;
    }
    .user-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-trigger {
    display: flex;
    align-items: center;
    text-decoration: none;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.dropdown-trigger:hover {
    background-color: rgba(0,0,0,0.05);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 8px 0;
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.user-dropdown.active .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 14px;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    width: 20px;
    margin-right: 10px;
    color: #6c757d;
}

.logout-btn {
    color: #dc3545;
}

.logout-btn:hover {
    background-color: #fff5f5;
    color: #dc3545;
}

.dropdown-divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 8px 0;
}
.hidden-item {
    display: none;
}
#intervenantsTable_filter {
    display: none !important; /* Masquer la boîte de recherche par défaut */
}

.SelectClass,.SumoSelect.open .search-txt,.SumoUnder {
  position:absolute;
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  top:0;
  left:0
}
.SumoSelect p {
  margin:0
}
.SumoSelect {
  width:100%;
}
.SelectBox {
  padding:5px 8px
}
.sumoStopScroll {
  overflow:hidden
}
.SumoSelect .hidden {
  display:none
}
.SumoSelect .search-txt {
  display:none;
  outline:0
}
.SumoSelect .no-match {
  display:none;
  padding:6px
}
.SumoSelect.open .search-txt {
  display:inline-block;
  width:100%;
  margin:0;
  padding:5px 8px;
  border:none;
  box-sizing:border-box;
  border-radius:5px
}
.SumoSelect.open>.search>label,.SumoSelect.open>.search>span {
visibility:hidden
}
.SelectClass,.SumoUnder {
  right:0;
  height:100%;
  width:100%;
  border:none;
  box-sizing:border-box;
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter:alpha(opacity=0);
  -moz-opacity:0;
  -khtml-opacity:0;
  opacity:0
}
.SelectClass {
  z-index:1
}
.SumoSelect .select-all>label,.SumoSelect>.CaptionCont,.SumoSelect>.optWrapper>.options li.opt label {
  user-select:none;
  -o-user-select:none;
  -moz-user-select:none;
  -khtml-user-select:none;
  -webkit-user-select:none
}
.SumoSelect {
  display:inline-block;
  position:relative;
  outline:0
}
.SumoSelect.open>.CaptionCont,.SumoSelect:focus>.CaptionCont,.SumoSelect:hover>.CaptionCont {
  box-shadow:0 0 2px #7799D0;
  border-color:#7799D0
} 
.SumoSelect>.CaptionCont {
  position:relative;
  border:1px solid #A4A4A4;
  min-height:14px;
  background-color:#fff;
  border-radius:2px;
  margin:0
}
.SumoSelect>.CaptionCont>span {
  display:block;
  padding-right:30px;
  text-overflow:ellipsis;
  white-space:nowrap;
  overflow:hidden;
  cursor:default
}
.SumoSelect>.CaptionCont>span.placeholder {
  color:#ccc;
  font-style:italic
}
.SumoSelect>.CaptionCont>label {
  position:absolute;
  top:0;
  right:0;
  bottom:0;
  width:30px
}
.SumoSelect>.CaptionCont>label>i {
  background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAANCAYAAABy6+R8AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wMdBhAJ/fwnjwAAAGFJREFUKM9jYBh+gBFKuzEwMKQwMDB8xaOWlYGB4T4DA0MrsuapDAwM//HgNwwMDDbYTJuGQ8MHBgYGJ1xOYGNgYJiBpuEpAwODHSF/siDZ+ISBgcGClEDqZ2Bg8B6CkQsAPRga0cpRtDEAAAAASUVORK5CYII=);
  background-position:center center;
  width:16px;
  height:16px;
  display:block;
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  margin:auto;
  background-repeat:no-repeat;
  opacity:.8
}
.SumoSelect>.optWrapper {
  display:none;
  z-index:1000;
  top:30px;
  width:100%;
  position:absolute;
  left:0;
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  box-sizing:border-box;
  background:#fff;
  border:1px solid #ddd;
  box-shadow:2px 3px 3px rgba(0,0,0,.11);
  border-radius:3px;
  overflow:hidden
}
.SumoSelect.open>.optWrapper {
  top:35px;
  display:block
}
.SumoSelect.open>.optWrapper.up {
  top:auto;
  bottom:100%;
  margin-bottom:5px
}
.SumoSelect>.optWrapper ul {
  list-style:none;
  display:block;
  padding:0;
  margin:0;
  overflow:auto
}
.SumoSelect>.optWrapper>.options {
  border-radius:2px;
  position:relative;
  max-height:250px
}
.SumoSelect>.optWrapper>.options li.group.disabled>label {
  opacity:.5
}
.SumoSelect>.optWrapper>.options li ul li.opt {
  padding-left:22px
}
.SumoSelect>.optWrapper.multiple>.options li ul li.opt {
  padding-left:50px
}
.SumoSelect>.optWrapper.isFloating>.options {
  max-height:100%;
  box-shadow:0 0 100px #595959
}
.SumoSelect>.optWrapper>.options li.opt {
  padding:6px;
  position:relative;
  border-bottom:1px solid #f5f5f5
}
.SumoSelect>.optWrapper>.options>li.opt:first-child {
  border-radius:2px 2px 0 0
}
.SumoSelect>.optWrapper>.options>li.opt:last-child {
  border-radius:0 0 2px 2px;
  border-bottom:none
}
.SumoSelect>.optWrapper>.options li.opt:hover {
  background-color:#E4E4E4
}
.SumoSelect>.optWrapper>.options li.opt.sel {
  background-color:#a1c0e4;
  border-bottom:1px solid #a1c0e4
}
.SumoSelect>.optWrapper>.options li label {
  text-overflow:ellipsis;
  white-space:nowrap;
  overflow:hidden;
  display:block;
  cursor:pointer
}
.SumoSelect>.optWrapper>.options li span {
  display:none
}
.SumoSelect>.optWrapper>.options li.group>label {
  cursor:default;
  padding:8px 6px;
  font-weight:700
}
.SumoSelect>.optWrapper.isFloating {
  position:fixed;
  top:0;
  left:0;
  right:0;
  width:90%;
  bottom:0;
  margin:auto;
  max-height:90%
}
.SumoSelect>.optWrapper>.options li.opt.disabled {
  background-color:inherit;
  pointer-events:none
}
.SumoSelect>.optWrapper>.options li.opt.disabled * {
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
  filter:alpha(opacity=50);
  -moz-opacity:.5;
  -khtml-opacity:.5;
  opacity:.5
}
.SumoSelect>.optWrapper.multiple>.options li.opt {
  padding-left:35px;
  cursor:pointer
}
.SumoSelect .select-all>span,.SumoSelect>.optWrapper.multiple>.options li.opt span {
  position:absolute;
  display:block;
  width:30px;
  top:0;
  bottom:0;
  margin-left:-35px
}
.SumoSelect .select-all>span i,.SumoSelect>.optWrapper.multiple>.options li.opt span i {
  position:absolute;
  margin:auto;
  left:0;
  right:0;
  top:0;
  bottom:0;
  width:14px;
  height:14px;
  border:1px solid #AEAEAE;
  border-radius:2px;
  box-shadow:inset 0 1px 3px rgba(0,0,0,.15);
  background-color:#fff
}
.SumoSelect>.optWrapper>.MultiControls {
  display:none;
  border-top:1px solid #ddd;
  background-color:#fff;
  box-shadow:0 0 2px rgba(0,0,0,.13);
  border-radius:0 0 3px 3px
}
.SumoSelect>.optWrapper.multiple.isFloating>.MultiControls {
  display:block;
  margin-top:5px;
  position:absolute;
  bottom:0;
  width:100%
}
.SumoSelect>.optWrapper.multiple.okCancelInMulti>.MultiControls {
  display:block
}
.SumoSelect>.optWrapper.multiple.okCancelInMulti>.MultiControls>p {
  padding:6px
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p {
  display:inline-block;
  cursor:pointer;
  padding:12px;
  width:50%;
  box-sizing:border-box;
  text-align:center
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p:hover {
  background-color:#f1f1f1
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p.btnOk {
  border-right:1px solid #DBDBDB;
  border-radius:0 0 0 3px 
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p.btnCancel {
  border-radius:0 0 3px
}
.SumoSelect>.optWrapper.isFloating>.options li.opt {
  padding:12px 6px 
}
.SumoSelect>.optWrapper.multiple.isFloating>.options li.opt {
  padding-left:35px
}
.SumoSelect>.optWrapper.multiple.isFloating {
  padding-bottom:43px
}
.SumoSelect .select-all.partial>span i,.SumoSelect .select-all.selected>span i,.SumoSelect>.optWrapper.multiple>.options li.opt.selected span i {
  background-color:#11a911;
  box-shadow:none;
  border-color:transparent;
  background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAGCAYAAAD+Bd/7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNXG14zYAAABMSURBVAiZfc0xDkAAFIPhd2Kr1WRjcAExuIgzGUTIZ/AkImjSofnbNBAfHvzAHjOKNzhiQ42IDFXCDivaaxAJd0xYshT3QqBxqnxeHvhunpu23xnmAAAAAElFTkSuQmCC);
  background-repeat:no-repeat;
  background-position:center center
}
.SumoSelect.disabled {
  opacity:.7;
  cursor:not-allowed
}
.SumoSelect.disabled>.CaptionCont {
  border-color:#ccc;
  box-shadow:none
}
.SumoSelect .select-all {
  border-radius:3px 3px 0 0;
  position:relative;
  border-bottom:1px solid #ddd;
  background-color:#fff;
  padding:8px 0 3px 35px;
  height:20px;
  cursor:pointer
}
.SumoSelect .select-all>label,.SumoSelect .select-all>span i {
  cursor:pointer
}
.SumoSelect .select-all.partial>span i {
  background-color:#ccc
}
.SumoSelect>.optWrapper>.options li.optGroup {
  padding-left:5px;
  text-decoration:underline
}

.form-control-plaintext {
    min-height: 38px;
    border: 1px solid #ced4da;
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
}
.bg-light {
    background-color: #f8f9fa !important;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    vertical-align: top;
}
.modal-xl {
    max-width: 1140px !important;
}
.btn-group-sm .btn {
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  padding: 0;
  box-sizing: border-box;
}
.btn-group-sm i {
  font-size:12px;
}
</style>
</head>
<body class="hold-transition sidebar-mini">
  
<!-- Site wrapper -->
<div class="wrapper">
    <x-header />
    <x-side-nav />
                @yield('content')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<!-- DataTables & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<!-- Validation & Select2 -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>



<!-- Dans layouts/app.blade.php avant la fermeture du body -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Scripts personnalisés (chargés en dernier) -->

<script src="{{ asset('assets/custom/users.js') }}"></script>
<script src="{{ asset('assets/custom/timesheets.js') }}"></script>    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle hiding selected items
    function setupSelectHiding(mainSelectId, multiSelectId) {
        const mainSelect = document.getElementById(mainSelectId);
        const multiSelect = document.getElementById(multiSelectId);
        
        if (!mainSelect || !multiSelect) return;
        
        function updateMultiSelect() {
            const selectedId = mainSelect.value;
            
            // Reset all options to be visible
            for (let option of multiSelect.options) {
                option.classList.remove('hidden-item');
            }
            
            // Hide the selected item
            if (selectedId) {
                for (let option of multiSelect.options) {
                    if (option.value === selectedId) {
                        option.classList.add('hidden-item');
                        option.selected = false;
                    }
                }
            }
        }
        
        mainSelect.addEventListener('change', updateMultiSelect);
        updateMultiSelect(); // Initialize
    }
    
    // Setup for Intervenants tab
    setupSelectHiding('client_id', 'autres_intervenants');
    setupSelectHiding('client_id', 'intervenantList');
    
    // Setup for Équipe tab
    setupSelectHiding('avocat_id', 'equipe_supplementaire');
});
</script>
<script>
  $(document).ready(function() {
    // Gestion du toggle du menu
    $('#pushmenu').on('click', function() {
        $('body').toggleClass('sidebar-collapse');
        
        // Sauvegarder l'état dans le localStorage
        const isCollapsed = $('body').hasClass('sidebar-collapse');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    // Restaurer l'état du sidebar au chargement de la page
    function restoreSidebarState() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            $('body').addClass('sidebar-collapse');
        } else {
            $('body').removeClass('sidebar-collapse');
        }
    }

    // Appeler au chargement
    restoreSidebarState();

    // Gestion responsive - fermer le sidebar sur les petits écrans
    function handleResponsive() {
        if ($(window).width() < 768) {
            $('body').addClass('sidebar-collapse');
        }
    }

    // Appeler au redimensionnement
    $(window).on('resize', handleResponsive);
    handleResponsive(); // Appeler au chargement initial
});
</script>
</body>
</html>