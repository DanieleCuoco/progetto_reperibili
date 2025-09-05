<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Amministratore</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <h1><i class="bi bi-gear-fill"></i> Sistema Gestione Reperibilità</h1>
            <div class="user-controls">
                <span><i class="bi bi-person-circle"></i> Benvenuto, {{ Auth::guard('admin')->user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-list"></i> Menu
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#"><i class="bi bi-house-fill"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-diagram-3-fill"></i> Gestione Reperibili</a></li>
            <li><a href="{{ route('admin.reparti.index') }}"><i class="bi bi-diagram-3-fill"></i> Gestione Reparti</a></li>
            <li><a href="#"><i class="bi bi-check2-square"></i> Gestione Modifiche</a></li>
            <li><a href="#"><i class="bi bi-calendar-event"></i> Calendario</a></li>
        </ul>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
        </div>

        <div class="card-grid">
            <!-- Sezione Gestione Reperibili -->
            <div class="card">
                <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                <h2>Gestione Reperibili</h2>
                <p>Gestisci il personale reperibile, aggiungi nuovi reperibili, modifica o disattiva quelli esistenti.</p>
                <a href="{{ route('admin.reperibili.index') }}" class="card-link"><i class="bi bi-arrow-right-circle"></i> Accedi</a>
            </div>

            <!-- Sezione Gestione Reparti -->
            <div class="card">
    <div class="card-icon"><i class="bi bi-diagram-3-fill"></i></div>
    <h2>Gestione Reparti</h2>
    <p>Gestisci i reparti settoriali e applicativi, crea nuovi reparti e assegna personale.</p>
    <a href="{{ route('admin.reparti.index') }}" class="card-link"><i class="bi bi-arrow-right-circle"></i> Accedi</a>
</div>

            <!-- Sezione Gestione Modifiche -->
            <div class="card">
                <div class="card-icon"><i class="bi bi-check2-square"></i></div>
                <h2>Gestione Modifiche</h2>
                <p>Approva o rifiuta le richieste di modifica, gestisci le notifiche e i cambiamenti.</p>
                <a href="#" class="card-link"><i class="bi bi-arrow-right-circle"></i> Accedi</a>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="stats-container">
            <h2><i class="bi bi-bar-chart-fill"></i> Statistiche</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
                    <h3>Reperibili Attivi</h3>
                    <div class="stat-value">{{ $reperibili_attivi }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-building"></i></div>
                    <h3>Reparti</h3>
                    <div class="stat-value">{{ $reparti }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <h3>Modifiche in attesa</h3>
                    <div class="stat-value">{{ $modifiche_in_attesa }}</div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistema Gestione Reperibilità - Tutti i diritti riservati</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funzione per animare il conteggio
            function animateCount(element, target) {
                const duration = 800; // Durata dell'animazione in millisecondi
                const frameDuration = 1000/60; // 60fps
                const totalFrames = Math.round(duration / frameDuration);
                let frame = 0;
                
                const counter = setInterval(() => {
                    frame++;
                    // Calcolo del valore corrente usando una funzione di easing
                    const progress = frame / totalFrames;
                    const currentCount = Math.round(easeOutQuad(progress) * target);
                    
                    element.textContent = currentCount;
                    
                    if (frame === totalFrames) {
                        clearInterval(counter);
                        element.textContent = target; // Assicuriamoci che il valore finale sia esatto
                    }
                }, frameDuration);
            }
            
            // Funzione di easing per un'animazione più naturale
            function easeOutQuad(t) {
                return t * (2 - t);
            }
            
            // Seleziona tutti gli elementi con classe stat-value
            const statValues = document.querySelectorAll('.stat-value');
            
            // Anima ciascun valore
            statValues.forEach(element => {
                const targetValue = parseInt(element.textContent.trim());
                element.textContent = '0'; // Inizia da zero
                animateCount(element, targetValue);
            });
        });
    </script>
</body>
</html>