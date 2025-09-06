<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Reparto</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('reperibili.css') }}">
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
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-fill"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-people-fill"></i> Gestione Reperibili</a></li>
            <li class="active"><a href="{{ route('admin.reparti.index') }}"><i class="bi bi-diagram-3-fill"></i> Gestione Reparti</a></li>
            <li><a href="#"><i class="bi bi-check2-square"></i> Gestione Modifiche</a></li>
            <li><a href="#"><i class="bi bi-calendar-event"></i> Calendario</a></li>
        </ul>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2><i class="bi bi-eye"></i> Dettagli Reparto</h2>
        </div>

        <div class="detail-container">
            <div class="detail-card">
                <div class="detail-header">
                    <h3>{{ $reparto->nome }}</h3>
                    <span class="detail-status {{ $reparto->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $reparto->is_active ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                
                <div class="detail-body">
                    <div class="detail-item">
                        <span class="detail-label">ID:</span>
                        <span class="detail-value">{{ $reparto->id }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Codice:</span>
                        <span class="detail-value">{{ $reparto->codice }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Descrizione:</span>
                        <span class="detail-value">{{ $reparto->descrizione ?? 'Nessuna descrizione' }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Creato il:</span>
                        <span class="detail-value">{{ $reparto->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Aggiornato il:</span>
                        <span class="detail-value">{{ $reparto->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <!-- Sezione Reperibili Affiliati -->
                <div class="reperibili-section">
                    <h4>
                        <i class="bi bi-people-fill"></i> Reperibili Affiliati
                    </h4>
                    
                    @if($reparto->reperibili->count() > 0)
                        <div class="reperibili-grid">
                            @foreach($reparto->reperibili as $reperibile)
                                <div class="reperibile-card">
                                    <div class="reperibile-info">
                                        <div class="reperibile-field">
                                            <span class="reperibile-label">Nome:</span> 
                                            <span class="reperibile-value">{{ $reperibile->name }}</span>
                                        </div>
                                        <div class="reperibile-field">
                                            <span class="reperibile-label">Email:</span> 
                                            <span class="reperibile-value">{{ $reperibile->email }}</span>
                                        </div>
                                        <div class="reperibile-field">
                                            <span class="reperibile-label">Telefono:</span> 
                                            <span class="reperibile-value">{{ $reperibile->phone ?: 'Nessun telefono' }}</span>
                                        </div>
                                    </div>
                                    <div class="reperibile-actions">
                                        <a href="{{ route('admin.reperibili.show', $reperibile) }}" class="btn-view">
                                            <i class="bi bi-eye"></i> Visualizza
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-reperibili">
                            <i class="bi bi-info-circle"></i>
                            <p>Nessun reperibile associato a questo reparto.</p>
                        </div>
                    @endif
                </div>
                
                <div class="detail-footer">
                    <a href="{{ route('admin.reparti.edit', $reparto) }}" class="btn-edit">
                        <i class="bi bi-pencil"></i> Modifica
                    </a>
                    <a href="{{ route('admin.reparti.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Torna all'elenco
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistema Gestione Reperibilità - Tutti i diritti riservati</p>
        </div>
    </footer>
</body>
</html>