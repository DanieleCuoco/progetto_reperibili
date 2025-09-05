<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Reperibile</title>
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('reperibili.css') }}">
    <link rel="stylesheet" href="{{ asset('reperibile_dashboard.css') }}">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Dashboard Reperibile</h1>
            <div class="user-info">
                <span>Benvenuto, {{ $reperibile->name }}</span>
                <form method="POST" action="{{ route('reperibile.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </header>
        
        <main>
            <div>
                <div class="dashboard-card">
                    <h2>I tuoi dati</h2>
                    <div class="info-item">
                        <strong>Nome:</strong> {{ $reperibile->name }}
                    </div>
                    <div class="info-item">
                        <strong>Email:</strong> {{ $reperibile->email }}
                    </div>
                    <div class="info-item">
                        <strong>Telefono:</strong> {{ $reperibile->phone }}
                    </div>
                    @if($reperibile->reparto)
                    <div class="info-item">
                        <strong>Reparto:</strong> {{ $reperibile->reparto->nome }}
                    </div>
                    @endif
                </div>
            </div>
            
            <div>
                <div class="dashboard-card">
                    <h2>I tuoi turni di reperibilità</h2>
                    
                    <div class="turni-container">
                        @forelse($reperibile->turni()->orderBy('data_inizio', 'desc')->take(5)->get() as $turno)
                            <div class="turno-item">
                                <div class="turno-date">Dal {{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') }}</div>
                                <div class="turno-time">Orario: {{ $turno->ora_inizio }} - {{ $turno->ora_fine }}</div>
                                @if($turno->note)
                                    <div class="turno-note">Note: {{ $turno->note }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">
                                <p>Non hai ancora turni di reperibilità programmati.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h2>Inserisci nuovo turno</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('reperibile.turni.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="data_inizio">Data inizio</label>
                            <input type="date" id="data_inizio" name="data_inizio" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="data_fine">Data fine</label>
                            <input type="date" id="data_fine" name="data_fine" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ora_inizio">Ora inizio</label>
                            <input type="time" id="ora_inizio" name="ora_inizio" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ora_fine">Ora fine</label>
                            <input type="time" id="ora_fine" name="ora_fine" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="note">Note (opzionale)</label>
                            <textarea id="note" name="note" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Salva turno</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>