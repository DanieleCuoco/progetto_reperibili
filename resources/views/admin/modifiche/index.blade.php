<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Reperibilità - Gestione Modifiche</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('modifiche.css') }}">
    <link rel="stylesheet" href="{{ asset('modifiche.css') }}?v={{ time() }}">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>Sistema Reperibilità</h1>
                </div>
                <div class="user-info">
                    <div class="notification-container">
                        <button class="notification-btn" id="notificationBtn">
                            <i class="bi bi-bell"></i>
                            @if(count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni) > 0)
                                <span class="notification-badge" id="notificationBadge">{{ count($nuoviTurni) + count($modificheTurni) + count($cancellazioniTurni) }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h3>Notifiche</h3>
                                <button class="mark-all-read" id="markAllRead">Segna tutte come lette</button>
                            </div>
                            <div class="notification-list">
                                @if(count($nuoviTurni) > 0)
                                    @foreach($nuoviTurni as $turno)
                                        <div class="notification-item unread">
                                            <div class="notification-icon">
                                                <i class="bi bi-plus-square"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p>Nuovo turno da {{ $turno->reperibile ? $turno->reperibile->name : 'Reperibile non trovato' }}</p>
                                                @if($turno->reperibile && $turno->reperibile->reparto)
                                                    <small>Reparto: {{ $turno->reperibile->reparto->nome }}</small>
                                                @endif
                                                <span class="notification-time">{{ $turno->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                
                                @if(count($modificheTurni) > 0)
                                    @foreach($modificheTurni as $turno)
                                        <div class="notification-item unread">
                                            <div class="notification-icon">
                                                <i class="bi bi-pencil-square"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p>Modifica turno da {{ $turno->reperibile ? $turno->reperibile->name : 'Reperibile non trovato' }}</p>
                                                <span class="notification-time">{{ $turno->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <span>Admin</span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-people"></i> Reperibili</a></li>
                    <li><a href="{{ route('admin.reparti.index') }}"><i class="bi bi-building"></i> Reparti</a></li>
                    <li><a href="{{ route('admin.modifiche.index') }}" class="active"><i class="bi bi-pencil-square"></i> Gestione Modifiche</a></li>
                </ul>
            </nav>
        </div>

        <main class="main-content">
            <div class="page-header">
                <h2>Gestione Modifiche Reperibilità</h2>
                <p>Gestisci le richieste di modifica dei turni di reperibilità</p>
            </div>

            <!-- Sezione Nuovi Turni -->
            @if(count($nuoviTurni) > 0)
                <section class="modifiche-section">
                    <h3><i class="bi bi-plus-square"></i> Nuovi Turni da Approvare ({{ count($nuoviTurni) }})</h3>
                    <div class="cards-grid">
                        @foreach($nuoviTurni as $turno)
                            <div class="modifica-card">
                                <div class="card-header">
                                    <h4>{{ $turno->reperibile ? $turno->reperibile->name : 'Reperibile non trovato' }}</h4>
                                    <span class="status-badge status-pending">In Attesa</span>
                                </div>
                                <div class="card-body">
                                    @if($turno->reperibile && $turno->reperibile->reparto)
                                        <p><strong>Reparto:</strong> {{ $turno->reperibile->reparto->nome }}</p>
                                    @endif
                                    <p><strong>Data Inizio:</strong> {{ $turno->data_inizio ? \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Data Fine:</strong> {{ $turno->data_fine ? \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Orario:</strong> {{ $turno->ora_inizio && $turno->ora_fine ? $turno->ora_inizio . ' - ' . $turno->ora_fine : 'Orario non disponibile' }}</p>
                                    @if($turno->note)
                                        <p><strong>Note:</strong> {{ $turno->note }}</p>
                                    @endif
                                    <p><strong>Richiesto il:</strong> {{ $turno->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="card-actions">
                                    <form method="POST" action="{{ route('admin.modifiche.approva', $turno->id) }}" class="action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-approve">
                                            <i class="bi bi-check-circle"></i> Approva
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.modifiche.rifiuta', $turno->id) }}" class="action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-reject">
                                            <i class="bi bi-x-circle"></i> Rifiuta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="modifiche-section">
                    <h3><i class="bi bi-plus-square"></i> Nuovi Turni da Approvare</h3>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Nessun nuovo turno da approvare</p>
                    </div>
                </section>
            @endif

            <!-- Sezione Modifiche Turni -->
            @if(count($modificheTurni) > 0)
                <section class="modifiche-section">
                    <h3><i class="bi bi-pencil-square"></i> Modifiche Turni da Approvare ({{ count($modificheTurni) }})</h3>
                    <div class="cards-grid">
                        @foreach($modificheTurni as $turno)
                            <div class="modifica-card">
                                <div class="card-header">
                                    <h4>{{ $turno->reperibile ? $turno->reperibile->name : 'Reperibile non trovato' }}</h4>
                                    <span class="status-badge status-modified">Modificato</span>
                                </div>
                                <div class="card-body">
                                    <p><strong>Data Inizio:</strong> {{ $turno->data_inizio ? \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Data Fine:</strong> {{ $turno->data_fine ? \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Orario:</strong> {{ $turno->ora_inizio && $turno->ora_fine ? $turno->ora_inizio . ' - ' . $turno->ora_fine : 'Orario non disponibile' }}</p>
                                    @if($turno->note)
                                        <p><strong>Note:</strong> {{ $turno->note }}</p>
                                    @endif
                                    <p><strong>Modificato il:</strong> {{ $turno->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="card-actions">
                                    <!-- Pulsanti azione modifiche -->
                                    <a href="{{ route('modifiche.approva', $turno->id) }}"
                                       onclick="event.preventDefault(); document.getElementById('approva-form-{{ $turno->id }}').submit();"
                                       class="btn-approve">
                                        <i class="bi bi-check-circle"></i> APPROVA
                                    </a>
                                    <form id="approva-form-{{ $turno->id }}" action="{{ route('modifiche.approva', $turno->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                    <a href="{{ route('modifiche.rifiuta', $turno->id) }}"
                                       onclick="event.preventDefault(); document.getElementById('rifiuta-form-{{ $turno->id }}').submit();"
                                       class="btn-reject">
                                        <i class="bi bi-x-circle"></i> RIFIUTA
                                    </a>
                                    <form id="rifiuta-form-{{ $turno->id }}" action="{{ route('modifiche.rifiuta', $turno->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="modifiche-section">
                    <h3><i class="bi bi-pencil-square"></i> Modifiche Turni da Approvare</h3>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Nessuna modifica turno da approvare</p>
                    </div>
                </section>
            @endif

            <!-- Sezione Cancellazioni -->
            @if(count($cancellazioniTurni) > 0)
                <section class="modifiche-section">
                    <h3><i class="bi bi-trash"></i> Cancellazioni da Approvare ({{ count($cancellazioniTurni) }})</h3>
                    <div class="cards-grid">
                        @foreach($cancellazioniTurni as $turno)
                            <div class="modifica-card">
                                <div class="card-header">
                                    <h4>{{ $turno->reperibile ? $turno->reperibile->name : 'Reperibile non trovato' }}</h4>
                                    <span class="status-badge status-cancelled">Da Cancellare</span>
                                </div>
                                <div class="card-body">
                                    <p><strong>Data Inizio:</strong> {{ $turno->data_inizio ? \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Data Fine:</strong> {{ $turno->data_fine ? \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') : 'Data non disponibile' }}</p>
                                    <p><strong>Orario:</strong> {{ $turno->ora_inizio && $turno->ora_fine ? $turno->ora_inizio . ' - ' . $turno->ora_fine : 'Orario non disponibile' }}</p>
                                    @if($turno->note)
                                        <p><strong>Note:</strong> {{ $turno->note }}</p>
                                    @endif
                                    <p><strong>Richiesta cancellazione:</strong> {{ $turno->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="card-actions">
                                    <form method="POST" action="{{ route('admin.modifiche.approva', $turno->id) }}" class="action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-approve">
                                            <i class="bi bi-check-circle"></i> Approva Cancellazione
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.modifiche.rifiuta', $turno->id) }}" class="action-form">
                                        @csrf
                                        <button type="submit" class="btn btn-reject">
                                            <i class="bi bi-x-circle"></i> Rifiuta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="modifiche-section">
                    <h3><i class="bi bi-trash"></i> Cancellazioni da Approvare</h3>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Nessuna cancellazione da approvare</p>
                    </div>
                </section>
            @endif
        </main>
    </div>

    <script src="{{ asset('modifiche.js') }}"></script>
</body>
</html>