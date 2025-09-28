<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Reparti</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('reperibili.css') }}">
    <link rel="stylesheet" href="{{ asset('reparti.css') }}">
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
            <li><a href="{{ route('admin.modifiche.index') }}"><i class="bi bi-check2-square"></i> Gestione Modifiche</a></li>
            <li><a href="{{ route('users.calendar') }}"><i class="bi bi-calendar-event"></i> Calendario</a></li>
        </ul>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2><i class="bi bi-diagram-3-fill"></i> Gestione Reparti</h2>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="top-bar">
            <div class="search-container">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cerca reparto...">
            </div>
            <a href="{{ route('admin.reparti.create') }}" class="btn-add">
                <i class="bi bi-plus-circle"></i> Aggiungi Reparto
            </a>
        </div>

        <div class="reparti-grid">
            @forelse($reparti as $reparto)
            <div class="reparto-card" data-nome="{{ $reparto->nome }}" data-codice="{{ $reparto->codice }}">
                <div class="reparto-icon">
                    @php
                        // Assegna icone in base al nome o al codice del reparto
                        $iconClass = 'icon-default';
                        $iconName = 'bi-diagram-3';
                        
                        // Mappa dei nomi/codici dei reparti alle icone
                        $iconMap = [
                            'storage' => ['class' => 'icon-storage', 'icon' => 'bi-hdd-rack'],
                            'db' => ['class' => 'icon-database', 'icon' => 'bi-database'],
                            'database' => ['class' => 'icon-database', 'icon' => 'bi-database'],
                            'network' => ['class' => 'icon-network', 'icon' => 'bi-ethernet'],
                            'security' => ['class' => 'icon-security', 'icon' => 'bi-shield-lock'],
                            'dev' => ['class' => 'icon-development', 'icon' => 'bi-code-square'],
                            'development' => ['class' => 'icon-development', 'icon' => 'bi-code-square'],
                            'support' => ['class' => 'icon-support', 'icon' => 'bi-headset'],
                            'management' => ['class' => 'icon-management', 'icon' => 'bi-briefcase'],
                            'research' => ['class' => 'icon-research', 'icon' => 'bi-lightbulb'],
                            'operations' => ['class' => 'icon-operations', 'icon' => 'bi-gear-wide-connected'],
                            'microsoft' => ['class' => 'icon-microsoft', 'icon' => 'bi-windows'],
                            'sistemi microsoft' => ['class' => 'icon-microsoft', 'icon' => 'bi-windows'],
                            'schedulazione' => ['class' => 'icon-schedule-host', 'icon' => 'bi-calendar-check'],
                            'schedulazione host' => ['class' => 'icon-schedule-host', 'icon' => 'bi-calendar-check'],
                            'oracle' => ['class' => 'icon-oracle', 'icon' => 'bi-database-fill'],
                            'unix' => ['class' => 'icon-schedule-unix', 'icon' => 'bi-terminal'],
                            'zos' => ['class' => 'icon-zos', 'icon' => 'bi-server'],
                            'file transfer' => ['class' => 'icon-file-transfer', 'icon' => 'bi-file-earmark-arrow-up'],
                            'mssql' => ['class' => 'icon-mssql', 'icon' => 'bi-database-fill-gear'],
                            'sql server' => ['class' => 'icon-sql-server', 'icon' => 'bi-database-fill-gear'],
                            'cloud' => ['class' => 'icon-cloud', 'icon' => 'bi-cloud'],
                            'monitoring' => ['class' => 'icon-monitoring', 'icon' => 'bi-graph-up']
                        ];
                        
                        // Cerca corrispondenze nel nome o nel codice
                        $nome = strtolower($reparto->nome);
                        $codice = strtolower($reparto->codice);
                        
                        foreach ($iconMap as $key => $value) {
                            if (str_contains($nome, $key) || str_contains($codice, $key)) {
                                $iconClass = $value['class'];
                                $iconName = $value['icon'];
                                break;
                            }
                        }
                    @endphp
                    <i class="bi {{ $iconName }} {{ $iconClass }}"></i>
                </div>
                <div class="reparto-info">
                    <h3>{{ $reparto->nome }}</h3>
                    <div class="reparto-code">Codice: {{ $reparto->codice }}</div>
                    <p>{{ Str::limit($reparto->descrizione, 100) }}</p>
                    <div class="reparto-status">
                        @if($reparto->is_active)
                        <span class="status-active">Attivo</span>
                        @else
                        <span class="status-inactive">Inattivo</span>
                        @endif
                    </div>
                </div>
                <div class="reparto-actions">
                    <a href="{{ route('admin.reparti.show', $reparto) }}" class="action-btn view-btn">
                        <i class="bi bi-eye"></i> Visualizza
                    </a>
                    <a href="{{ route('admin.reparti.edit', $reparto) }}" class="action-btn edit-btn">
                        <i class="bi bi-pencil"></i> Modifica
                    </a>
                    <form action="{{ route('admin.reparti.destroy', $reparto) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo reparto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn">
                            <i class="bi bi-trash"></i> Elimina
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="no-results">
                <i class="bi bi-exclamation-circle"></i>
                <p>Nessun reparto trovato</p>
            </div>
            @endforelse
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistema Gestione Reperibilità Daniele Cuoco - Tutti i diritti riservati</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const repartoCards = document.querySelectorAll('.reparto-card');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            repartoCards.forEach(card => {
                const nome = card.dataset.nome.toLowerCase();
                const codice = card.dataset.codice.toLowerCase();
                
                if (nome.includes(searchTerm) || codice.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    </script>
</body>
</html>