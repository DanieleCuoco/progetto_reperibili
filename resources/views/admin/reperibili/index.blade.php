<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Reperibili</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('reperibili.css') }}">
    {{-- <style>
        .table-container {
            background-color: #1a1a1a;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }
        
        th {
            background-color: #333;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #00bfff;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #444;
        }
        
        tr:hover {
            background-color: #2a2a2a;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view, .btn-edit, .btn-delete {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;

        }
        
        .btn-view {
            background-color: #2c3e50;
            color: white;
        }
        
        .btn-edit {
            background-color: #2980b9;
            color: white;
        }
        
        .btn-delete {
            background-color: #c0392b;
            color: white;
        }
        
        .btn-view:hover, .btn-edit:hover, .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
        }
        
        .btn-add {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            background-color: #2ecc71;
            transform: translateY(-2px);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
        }
        
        .status-active, .status-inactive {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
        }
        
        .status-active {
            background-color: #27ae60;
            color: white;
        }
        
        .status-inactive {
            background-color: #7f8c8d;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.2);
            border-left: 4px solid #27ae60;
            color: #2ecc71;
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
            <h2><i class="bi bi-people-fill"></i> Gestione Reperibili</h2>
        </div>

      @if(session('success'))
<div class="alert alert-success hidden">
    {{ session('success') }}
</div>
@endif

        <a href="{{ route('admin.reperibili.create') }}" class="btn-add">
            <i class="bi bi-plus-circle"></i> Aggiungi Reperibile
        </a>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Reparto</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reperibili as $reperibile)
                    <tr>
                        <td>{{ $reperibile->id }}</td>
                        <td>{{ $reperibile->name }}</td>
                        <td>{{ $reperibile->username }}</td>
                        <td>{{ $reperibile->email }}</td>
                        <td>{{ $reperibile->phone ?? 'N/A' }}</td>
                        <td>{{ $reperibile->department }}</td>
                        <td>
                            @if($reperibile->is_active)
                            <span class="status-active">Attivo</span>
                            @else
                            <span class="status-inactive">Inattivo</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.reperibili.show', $reperibile) }}" class="btn-view">
                                <i class="bi bi-eye"></i> Visualizza
                            </a>
                            <a href="{{ route('admin.reperibili.edit', $reperibile) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i> Modifica
                            </a>
                            <form action="{{ route('admin.reperibili.destroy', $reperibile) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo reperibile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash"></i> Elimina
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Nessun reperibile trovato</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistema Gestione Reperibilità - Tutti i diritti riservati</p>
        </div>
    </footer>

    <script src="{{ asset('header-animations.js') }}"></script>
</body>
</html>