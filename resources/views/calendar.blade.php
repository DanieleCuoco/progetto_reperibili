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
            </div>
            
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
                        <div class="day {{ $day['isToday'] ? 'today' : '' }}">
                            <span class="day-number">{{ $day['day'] }}</span>
                            <!-- Rimosso il codice per la visualizzazione dei turni -->
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
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script per aggiornare automaticamente la pagina a mezzanotte -->
    <script>
    function refreshAtMidnight() {
        var now = new Date();
        var currentDate = now.getDate();
        var night = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate() + 1, // il giorno successivo
            0, 0, 0 // a mezzanotte
        );
        var msToMidnight = night.getTime() - now.getTime();

        console.log('Pagina programmata per ricaricarsi a mezzanotte tra ' + msToMidnight + ' millisecondi');

        // Imposta un timeout per ricaricare la pagina a mezzanotte
        setTimeout(function() {
            console.log('Ricarico la pagina a mezzanotte!');
            window.location.href = window.location.pathname + '?nocache=' + new Date().getTime();
        }, msToMidnight);
        
        // Imposta anche un backup per ricaricare ogni 15 minuti
        setInterval(function() {
            console.log('Ricarico la pagina (backup ogni 15 minuti)');
            window.location.reload(true);
        }, 900000); // 15 minuti in millisecondi
        
        // Verifica ogni minuto se la data è cambiata
        setInterval(function() {
            var checkNow = new Date();
            if (checkNow.getDate() !== currentDate) {
                console.log('La data è cambiata! Ricarico immediatamente');
                window.location.reload(true);
            }
        }, 60000); // 1 minuto in millisecondi
    }

    // Esegui la funzione quando la pagina è caricata
    document.addEventListener('DOMContentLoaded', refreshAtMidnight);
</script>
</body>
</html>