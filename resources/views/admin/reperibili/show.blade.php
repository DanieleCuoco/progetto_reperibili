<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Reperibile</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('reperibili.css') }}">
    {{-- <style>
        .details-container {
            background-color: #1a1a1a;
            border-radius: 8px;
            padding: 30px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }
        
        .detail-group {
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
        }
        
        .detail-group:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #00bfff;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #fff;
            font-size: 1.1em;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .status-active {
            background-color: #27ae60;
            color: white;
        }
        
        .status-inactive {
            background-color: #7f8c8d;
            color: white;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-edit, .btn-back {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-edit {
            background-color: #2980b9;
            color: white;
        }
        
        .btn-back {
            background-color: #7f8c8d;
            color: white;
        }
        
        .btn-edit:hover, .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
        }
    </style> --}}
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
            <li class="active"><a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-people-fill"></i> Gestione Reperibili</a></li>
            <li><a href="{{ route('admin.reparti.index') }}"><i class="bi bi-diagram-3-fill"></i> Gestione Reparti</a></li>
            <li><a href="#"><i class="bi bi-check2-square"></i> Gestione Modifiche</a></li>
            <li><a href="#"><i class="bi bi-calendar-event"></i> Calendario</a></li>
        </ul>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2><i class="bi bi-person-badge"></i> Dettagli Reperibile</h2>
        </div>

        <div class="details-container">
            <div class="detail-group">
                <div class="detail-label">ID</div>
                <div class="detail-value">{{ $reperibile->id }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Nome</div>
                <div class="detail-value">{{ $reperibile->name }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Username</div>
                <div class="detail-value">{{ $reperibile->username }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $reperibile->email }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Telefono</div>
                <div class="detail-value">{{ $reperibile->phone ?? 'Non specificato' }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Reparto</div>
                <div class="detail-value">{{ $reperibile->department }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Stato</div>
                <div class="detail-value">
                    @if($reperibile->is_active)
                    <span class="status-badge status-active">Attivo</span>
                    @else
                    <span class="status-badge status-inactive">Inattivo</span>
                    @endif
                </div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Data Creazione</div>
                <div class="detail-value">{{ $reperibile->created_at ? $reperibile->created_at->format('d/m/Y H:i') : 'Non disponibile' }}</div>
            </div>
            
            <div class="detail-group">
                <div class="detail-label">Ultimo Aggiornamento</div>
                <div class="detail-value">{{ $reperibile->updated_at ? $reperibile->updated_at->format('d/m/Y H:i') : 'Non disponibile' }}</div>
            </div>
            
            <div class="btn-group">
               <a href="{{ route('admin.reperibili.edit', ['reperibile' => $reperibile->id]) }}" class="btn-edit">
                <i class="bi bi-pencil"></i> Modifica
            </a>
                <a href="{{ route('admin.reperibili.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Torna alla lista
                </a>
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