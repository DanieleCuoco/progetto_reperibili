<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Pagina Non Trovata | Reperibilità</title>
    <link rel="stylesheet" href="{{ asset('404-animation.css') }}">
</head>
<body>
    <main>
        <svg class="face" viewBox="0 0 320 380" width="320px" height="380px" aria-label="A 404 becomes a face, looks to the sides, and blinks. The 4s slide up, the 0 slides down, and then a mouth appears.">
            <g 
                fill="none" 
                stroke="currentcolor" 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="25"
            >
                <g class="face__eyes" transform="translate(0, 112.5)">
                    <g transform="translate(15, 0)">
                        <polyline class="face__eye-lid" points="37,0 0,120 75,120" />
                        <polyline class="face__pupil" points="55,120 55,155" stroke-dasharray="35 35" />
                    </g>
                    <g transform="translate(230, 0)">
                        <polyline class="face__eye-lid" points="37,0 0,120 75,120" />
                        <polyline class="face__pupil" points="55,120 55,155" stroke-dasharray="35 35" />
                    </g>
                </g>
                <rect class="face__nose" rx="4" ry="4" x="132.5" y="112.5" width="55" height="155" />
                <g stroke-dasharray="102 102" transform="translate(65, 334)">
                    <path class="face__mouth-left" d="M 0 30 C 0 30 40 0 95 0" stroke-dashoffset="-102" />
                    <path class="face__mouth-right" d="M 95 0 C 150 0 190 30 190 30" stroke-dashoffset="102" />
                </g>
            </g>
        </svg>
        
        <div class="error-content">
            <h2 class="error-title">Oops! Pagina Non Trovata</h2>
            <p class="error-message">
                Sembra che la pagina che stai cercando sia in reperibilità... 
                ma non risponde alla chiamata! 📱
            </p>
            <p class="error-submessage">
                Non preoccuparti, puoi tornare alle sezioni principali:
            </p>
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-home">
                    🏠 Home
                </a>
                @if(Route::has('reperibile.dashboard'))
                    <a href="{{ route('reperibile.dashboard') }}" class="btn btn-reperibile">
                        👨‍⚕️ Area Reperibile
                    </a>
                @endif
                @if(Route::has('users.calendar'))
                    <a href="{{ route('users.calendar') }}" class="btn btn-calendar">
                        📅 Calendario
                    </a>
                @endif
                @if(Route::has('admin.login'))
                    <a href="{{ route('admin.login') }}" class="btn btn-login">
                        🔐 Login Admin
                    </a>
                @endif
                @if(Route::has('reperibile.login'))
                    <a href="{{ route('reperibile.login') }}" class="btn btn-reperibile-login">
                        👩‍⚕️ Login Reperibile
                    </a>
                @endif
            </div>
            <div class="error-help">
                <p>💡 <strong>Suggerimento:</strong> Controlla l'URL o usa i pulsanti sopra per navigare</p>
            </div>
        </div>
    </main>
</body>
</html>