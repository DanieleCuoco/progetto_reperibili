<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Calendario dei turni di reperibilità">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Aggiungi questo meta tag per forzare il refresh ogni 5 minuti -->
    <meta http-equiv="refresh" content="300">
    <title>Calendario Reperibili - {{ time() }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('calendar.css') }}?v={{ time() }}" rel="stylesheet">
</head>
<body>
    <div class="fullscreen-container">
        <div class="calendar-container">
            <div class="calendar-header">
                <h1 class="display-4">{{ $calendar['month'] }}</h1>
                
                <!-- Aggiunta del selettore di reparto -->
                <div class="department-filter">
                    <form id="departmentForm" method="GET" action="{{ route('users.calendar') }}">
                        @if(request()->has('month'))
                        <input type="hidden" name="month" value="{{ request()->query('month') }}">
                        @endif
                        <select name="department" id="department-select" class="form-select" onchange="this.form.submit()">
                            <option value="">Tutti i reparti</option>
                            @foreach($reparti as $reparto)
                                <option value="{{ $reparto->codice }}" {{ request()->query('department') == $reparto->codice ? 'selected' : '' }}>
                                    {{ $reparto->nome }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- Resto del codice del calendario -->
            <div class="calendar-body">
                <div class="weekdays">
                    <div><i class="bi bi-sun-fill text-warning me-1"></i>Dom</div>
                    <div><i class="bi bi-calendar-week me-1"></i>Lun</div>
                    <div><i class="bi bi-calendar-week me-1"></i>Mar</div>
                    <div><i class="bi bi-calendar-week me-1"></i>Mer</div>
                    <div><i class="bi bi-calendar-week me-1"></i>Gio</div>
                    <div><i class="bi bi-calendar-week me-1"></i>Ven</div>
                    <div><i class="bi bi-moon-fill text-info me-1"></i>Sab</div>
                </div>
                
                <div class="days">
                    @foreach($calendar['days'] as $day)
                        <div class="day {{ $day['isToday'] ? 'today' : '' }}" 
                             @if($day['day']) onclick="showDayDetails('{{ $day['date'] }}', {{ json_encode($day['turni']) }})" @endif>
                            <span class="day-number">{{ $day['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="calendar-controls">
                <button class="btn-prev" onclick="location.href='{{ route('users.calendar', ['month' => 'prev']) }}'">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i>Mese Precedente
                </button>
                <button class="btn-today" onclick="location.href='{{ route('users.calendar') }}'">
                    <i class="bi bi-calendar-check-fill me-2"></i>Oggi
                </button>
                <button class="btn-next" onclick="location.href='{{ route('users.calendar', ['month' => 'next']) }}'">
                    <i class="bi bi-arrow-right-circle-fill me-2"></i>Mese Successivo
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal per i dettagli del giorno -->
    <div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-labelledby="dayDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dayDetailsModalLabel">Dettagli del giorno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="modalDate" class="mb-3"></h6>
                    <div id="reperibili-list">
                        <div class="no-reperibili">Nessun reperibile disponibile per questa data</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('calendar.js') }}?v={{ time() }}"></script>
</body>
</html>