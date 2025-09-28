@echo off
title Sistema Reperibili - Avvio in corso...
color 0A

echo ========================================
echo    SISTEMA GESTIONE REPERIBILI
echo ========================================
echo.
echo Controllo prerequisiti...

REM Verifica se PHP è installato
php --version >nul 2>&1
if errorlevel 1 (
    echo ERRORE: PHP non trovato nel PATH!
    echo Installa PHP o aggiungi al PATH di sistema.
    pause
    exit /b 1
)

echo ✓ PHP trovato
echo.

REM Vai alla directory del progetto
cd /d "%~dp0"

REM Verifica se esiste il file .env
if not exist ".env" (
    echo Creazione file di configurazione...
    copy ".env.example" ".env"
    echo ✓ File .env creato
)

REM Verifica se la chiave dell'app è generata
findstr /C:"APP_KEY=" .env | findstr /C:"APP_KEY=$" >nul
if not errorlevel 1 (
    echo Generazione chiave applicazione...
    php artisan key:generate
    echo ✓ Chiave applicazione generata
)

REM Esegui le migrazioni se necessario
echo Controllo database...
php artisan migrate --force >nul 2>&1
echo ✓ Database aggiornato

echo.
echo ========================================
echo    AVVIO SISTEMA REPERIBILI
echo ========================================
echo.
echo Server in avvio su: http://127.0.0.1:8000
echo.
echo Per fermare il server: Ctrl+C
echo Per aprire l'applicazione: http://127.0.0.1:8000
echo.

REM Apri il browser automaticamente dopo 3 secondi
start "" timeout /t 3 /nobreak >nul && start "" "http://127.0.0.1:8000"

REM Avvia il server Laravel
php artisan serve --host=127.0.0.1 --port=8000

pause