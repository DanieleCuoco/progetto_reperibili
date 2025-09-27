<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Reperibile</title>
    <link rel="stylesheet" href="{{ asset('reperibile_dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1><i class="bi bi-person-badge"></i> Dashboard Reperibile</h1>
                <form method="POST" action="{{ route('reperibile.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="dashboard-main">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Dati personali -->
            <div class="dashboard-card">
                <h2><i class="bi bi-person-fill"></i> I tuoi dati</h2>
                <div class="user-info">
                    <div><strong>Nome:</strong> {{ $reperibile->name }}</div>
                    <div><strong>Email:</strong> {{ $reperibile->email }}</div>
                    <div><strong>Telefono:</strong> {{ $reperibile->phone }}</div>
                    @if($reperibile->reparto)
                        <div><strong>Reparto:</strong> {{ $reperibile->reparto->nome }}</div>
                    @endif
                </div>
            </div>

            <!-- Form inserimento/modifica -->
            <div class="dashboard-card">
                <h2 id="formTitle"><i class="bi bi-plus-circle"></i> Inserisci nuovo turno</h2>
                
                <form method="POST" action="{{ route('reperibile.turni.store') }}" id="turnoForm">
                    @csrf
                    <input type="hidden" id="turnoId" name="turno_id">
                    <input type="hidden" id="formMethod" name="_method">
                    
                    <div class="form-group">
                        <label for="data_inizio"><i class="bi bi-calendar-event"></i> Data inizio</label>
                        <input type="date" id="data_inizio" name="data_inizio" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_fine"><i class="bi bi-calendar-check"></i> Data fine</label>
                        <input type="date" id="data_fine" name="data_fine" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ora_inizio"><i class="bi bi-clock"></i> Ora inizio</label>
                        <input type="time" id="ora_inizio" name="ora_inizio" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ora_fine"><i class="bi bi-clock-fill"></i> Ora fine</label>
                        <input type="time" id="ora_fine" name="ora_fine" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="note"><i class="bi bi-chat-text"></i> Note (opzionale)</label>
                        <textarea id="note" name="note" rows="3" placeholder="Aggiungi eventuali note..."></textarea>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-save"></i> Salva turno
                        </button>
                        <button type="button" class="btn-cancel" id="cancelBtn" onclick="resetForm()" style="display: none;">
                            <i class="bi bi-x-circle"></i> Annulla modifica
                        </button>
                    </div>
                </form>
            </div>

            <!-- Gestione turni esistenti -->
            <div class="dashboard-card">
                <h2><i class="bi bi-gear-fill"></i> Gestisci i tuoi turni</h2>
                
                @php
                    $turniModificabili = $reperibile->turni()->where('is_approved', '!=', 1)->orderBy('data_inizio', 'desc')->get();
                @endphp
                
                @if($turniModificabili->count() > 0)
                    <div class="turni-gestione">
                        @foreach($turniModificabili as $turno)
                            <div class="turno-gestione-item">
                                <div class="turno-info">
                                    <div class="turno-date">
                                        <i class="bi bi-calendar-range"></i>
                                        {{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') }}
                                    </div>
                                    <div class="turno-time">
                                        <i class="bi bi-clock"></i>
                                        {{ $turno->ora_inizio }} - {{ $turno->ora_fine }}
                                    </div>
                                    <div class="turno-status">
                                        @if($turno->status === 'nuovo' || $turno->status === null)
                                            <span class="status-badge status-new">Nuovo</span>
                                        @elseif($turno->status === 'modifica')
                                            <span class="status-badge status-modified">Modificato</span>
                                        @elseif($turno->status === 'cancellazione')
                                            <span class="status-badge status-cancellation">In cancellazione</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="turno-actions">
                                    @if($turno->status !== 'cancellazione')
                                        <button class="btn-edit" onclick="editTurno({{ $turno->id }}, '{{ $turno->data_inizio }}', '{{ $turno->data_fine }}', '{{ $turno->ora_inizio }}', '{{ $turno->ora_fine }}', '{{ $turno->note }}')">
                                            <i class="bi bi-pencil"></i> Modifica
                                        </button>
                                    @endif
                                    <form method="POST" action="{{ route('reperibile.turni.destroy', $turno) }}" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler cancellare questo turno?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="bi bi-trash"></i> 
                                            {{ $turno->status === 'cancellazione' ? 'Annulla richiesta' : 'Cancella' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                        <p>Non hai turni in attesa di approvazione da gestire.</p>
                    </div>
                @endif
            </div>

            <!-- Lista turni -->
            <div class="dashboard-card">
                <h2><i class="bi bi-list-check"></i> I tuoi turni di reperibilità</h2>
                
                <div class="turni-container">
                    @forelse($reperibile->turni()->orderBy('data_inizio', 'desc')->take(5)->get() as $turno)
                        <div class="turno-item">
                            <div class="turno-date">
                                <i class="bi bi-calendar-event"></i>
                                Dal {{ \Carbon\Carbon::parse($turno->data_inizio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($turno->data_fine)->format('d/m/Y') }}
                            </div>
                            <div class="turno-time">
                                <i class="bi bi-clock"></i>
                                Orario: {{ $turno->ora_inizio }} - {{ $turno->ora_fine }}
                            </div>
                            @if($turno->note)
                                <div class="turno-note">
                                    <i class="bi bi-chat-text"></i>
                                    Note: {{ $turno->note }}
                                </div>
                            @endif
                            <div class="turno-status">
                                @if($turno->is_approved == 1)
                                    <span class="status-badge status-approved">
                                        <i class="bi bi-check-circle"></i> Approvato
                                    </span>
                                @elseif($turno->is_approved == 2)
                                    <span class="status-badge status-rejected">
                                        <i class="bi bi-x-circle"></i> Rifiutato
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-clock-history"></i> In attesa
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                            <p>Non hai ancora turni di reperibilità programmati.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('reperibile-dashboard.js') }}"></script>
</body>
</html>