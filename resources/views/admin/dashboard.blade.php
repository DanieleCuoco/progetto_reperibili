<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Amministratore</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('header-animations.css') }}">
    <link rel="stylesheet" href="{{ asset('sidebar-animations.css') }}">
    <link rel="stylesheet" href="{{ asset('modifiche.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <header class="header-hidden">
        <div class="container header-content">
            <h1 class="header-title-hidden"><i class="bi bi-gear-fill"></i> Sistema Gestione Reperibilità</h1>
            <div class="user-controls user-controls-hidden">
                <!-- Campanello di notifica -->
                <div class="notification-container">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell"></i>
                        @if(count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni) > 0)
                            <span class="notification-badge" id="notificationBadge">{{ count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni) }}</span>
                        @endif
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifiche</h4>
                            <button class="close-notifications" id="closeNotifications">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        <div class="notification-content">
                            @if(count($nuoviTurni) > 0)
                                <div class="notification-section">
                                    <h5><i class="bi bi-plus-circle"></i> Nuovi Turni ({{ count($nuoviTurni) }})</h5>
                                    @foreach($nuoviTurni as $turno)
                                        <div class="notification-item">
                                            <p><strong>{{ $turno->reperibile->name }}</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y H:i') }}</p>
                                            <small>{{ $turno->reperibile->reparto ? $turno->reperibile->reparto->nome : 'Reparto non disponibile' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($modificheTurni) > 0)
                                <div class="notification-section">
                                    <h5><i class="bi bi-pencil-square"></i> Modifiche Turni ({{ count($modificheTurni) }})</h5>
                                    @foreach($modificheTurni as $turno)
                                        <div class="notification-item">
                                            <p><strong>{{ $turno->reperibile->name }}</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y H:i') }}</p>
                                            <small>{{ $turno->reperibile->reparto ? $turno->reperibile->reparto->nome : 'Reparto non disponibile' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($cancellazioniTurni) > 0)
                                <div class="notification-section">
                                    <h5><i class="bi bi-trash"></i> Cancellazioni Turni ({{ count($cancellazioniTurni) }})</h5>
                                    @foreach($cancellazioniTurni as $turno)
                                        <div class="notification-item">
                                            <p><strong>{{ $turno->reperibile->name }}</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y H:i') }}</p>
                                            <small>{{ $turno->reperibile->reparto ? $turno->reperibile->reparto->nome : 'Reparto non disponibile' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni) == 0)
                                <div class="no-notifications">
                                    <i class="bi bi-check-circle"></i>
                                    <p>Nessuna notifica</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Logout -->
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
            <li class="active"><a href="#"><i class="bi bi-house-fill"></i> <span>Dashboard</span></a></li>
            <li><a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-people-fill"></i> <span>Gestione Reperibili</span></a></li>
            <li><a href="{{ route('admin.reparti.index') }}"><i class="bi bi-diagram-3-fill"></i> <span>Gestione Reparti</span></a></li>
            <li><a href="{{ route('admin.modifiche.index') }}"><i class="bi bi-check2-square"></i> <span>Gestione Modifiche</span></a></li>
            <li><a href="{{ route('users.calendar') }}"><i class="bi bi-calendar-event"></i> <span>Calendario</span></a></li>
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
                <a href="{{ route('admin.modifiche.index') }}" class="card-link"><i class="bi bi-arrow-right-circle"></i> Accedi</a>
                @if($modifiche_in_attesa > 0)
                    <div class="card-notification">
                        <span class="notification-count">{{ $modifiche_in_attesa }}</span>
                    </div>
                @endif
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
                    <h3>Modifiche in Attesa</h3>
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
    
    <!-- Script per le animazioni e notifiche -->
    <script src="{{ asset('header-animations.js') }}"></script>
    <script src="{{ asset('sidebar-animations.js') }}"></script>
    <script src="{{ asset('modifiche.js') }}"></script>
</body>
</html>