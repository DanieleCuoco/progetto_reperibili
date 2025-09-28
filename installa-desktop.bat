@echo off
title Installazione Sistema Reperibili Desktop
color 0B

echo ========================================
echo   INSTALLAZIONE SISTEMA REPERIBILI
echo ========================================
echo.

REM Crea collegamento sul desktop
set "desktop=%USERPROFILE%\Desktop"
set "target=%~dp0avvia-reperibili.bat"
set "shortcut=%desktop%\Sistema Reperibili.lnk"

echo Creazione collegamento desktop con icona Vault Boy...

REM Crea il collegamento usando PowerShell con icona personalizzata
powershell -Command "$WshShell = New-Object -comObject WScript.Shell; $Shortcut = $WshShell.CreateShortcut('%shortcut%'); $Shortcut.TargetPath = '%target%'; $Shortcut.WorkingDirectory = '%~dp0'; $Shortcut.IconLocation = '%~dp0vault-boy.ico'; $Shortcut.Description = 'Sistema Gestione Reperibili - Vault Boy Edition'; $Shortcut.Save()"

if exist "%shortcut%" (
    echo ✓ Collegamento creato sul desktop: "Sistema Reperibili"
    echo ✓ Icona Vault Boy applicata con successo!
) else (
    echo ⚠ Errore nella creazione del collegamento
)

echo.
echo ========================================
echo        INSTALLAZIONE COMPLETATA
echo ========================================
echo.
echo 🎯 Il tuo Sistema Reperibili è ora pronto!
echo.
echo Ora puoi:
echo 1. Fare doppio clic su "Sistema Reperibili" sul desktop
echo 2. Oppure eseguire "avvia-reperibili.bat" da questa cartella
echo.
echo Il sistema si aprirà automaticamente nel browser!
echo Con l'icona del Vault Boy! 👍
echo.

pause