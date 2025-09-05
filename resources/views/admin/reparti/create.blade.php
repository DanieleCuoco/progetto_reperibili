<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Reparto</title>
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
            <li><a href="#"><i class="bi bi-check2-square"></i> Gestione Modifiche</a></li>
            <li><a href="#"><i class="bi bi-calendar-event"></i> Calendario</a></li>
        </ul>
    </div>

    <main class="main-content">
        <div class="page-header">
            <h2><i class="bi bi-plus-circle"></i> Aggiungi Reparto</h2>
        </div>

        <div class="form-container">
            <form action="{{ route('admin.reparti.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="nome">Nome Reparto</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required>
                    @error('nome')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="descrizione">Descrizione</label>
                    <input type="text" id="descrizione" name="descrizione" value="{{ old('descrizione') }}">
                    @error('descrizione')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="codice">Codice Reparto</label>
                    <input type="text" id="codice" name="codice" value="{{ old('codice') }}" required>
                    @error('codice')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Icona Reparto</label>
                    <div class="icon-selector">
                        @foreach($icons as $index => $icon)
                            <label class="icon-option" for="icon-{{ $index }}">
                                <input type="radio" id="icon-{{ $index }}" name="icon_name" value="{{ $icon['icon'] }}" class="icon-radio" {{ old('icon_name') == $icon['icon'] ? 'checked' : ($index == 0 ? 'checked' : '') }}>
                                <input type="hidden" name="icon_class" value="{{ $icon['class'] }}" class="icon-class-input" disabled>
                                <i class="bi {{ $icon['icon'] }} {{ $icon['class'] }}"></i>
                                <span>{{ $icon['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('icon_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active">Reparto Attivo</label>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle"></i> Salva Reparto
                    </button>
                    <a href="{{ route('admin.reparti.index') }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Annulla
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistema Gestione Reperibilità - Tutti i diritti riservati</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconOptions = document.querySelectorAll('.icon-option');
            const iconRadios = document.querySelectorAll('.icon-radio');
            const iconClassInputs = document.querySelectorAll('.icon-class-input');
            
            // Inizializza lo stato selezionato
            updateSelectedState();
            
            // Aggiungi event listener per ogni opzione
            iconOptions.forEach((option, index) => {
                option.addEventListener('click', function() {
                    // Seleziona il radio button corrispondente
                    iconRadios[index].checked = true;
                    
                    // Abilita l'input hidden corrispondente
                    iconClassInputs.forEach((input, i) => {
                        input.disabled = i !== index;
                    });
                    
                    // Aggiorna lo stato visivo
                    updateSelectedState();
                });
            });
            
            function updateSelectedState() {
                iconOptions.forEach((option, index) => {
                    if (iconRadios[index].checked) {
                        option.classList.add('selected');
                        iconClassInputs[index].disabled = false;
                    } else {
                        option.classList.remove('selected');
                        iconClassInputs[index].disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>