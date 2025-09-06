// Funzione per aggiornare automaticamente la pagina a mezzanotte
function refreshAtMidnight() {
    var now = new Date();
    var currentDate = now.getDate();
    var night = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate() + 1, // il giorno successivo
        0, 0, 0 // a mezzanotte
    );
    var msToMidnight = night.getTime() - now.getTime();

    console.log('Pagina programmata per ricaricarsi a mezzanotte tra ' + msToMidnight + ' millisecondi');

    // Imposta un timeout per ricaricare la pagina a mezzanotte
    setTimeout(function() {
        console.log('Ricarico la pagina a mezzanotte!');
        window.location.href = window.location.pathname + '?nocache=' + new Date().getTime();
    }, msToMidnight);
    
    // Imposta anche un backup per ricaricare ogni 15 minuti
    setInterval(function() {
        console.log('Ricarico la pagina (backup ogni 15 minuti)');
        window.location.reload(true);
    }, 900000); // 15 minuti in millisecondi
    
    // Verifica ogni minuto se la data è cambiata
    setInterval(function() {
        var checkNow = new Date();
        if (checkNow.getDate() !== currentDate) {
            console.log('La data è cambiata! Ricarico immediatamente');
            window.location.reload(true);
        }
    }, 60000); // 1 minuto in millisecondi
}

// Funzione per mostrare i dettagli del giorno nel modal
function showDayDetails(date, turni) {
    // Formatta la data in italiano
    const dateObj = new Date(date);
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = dateObj.toLocaleDateString('it-IT', options);
    
    // Imposta la data nel modal
    document.getElementById('modalDate').textContent = formattedDate;
    
    // Ottieni il container per la lista dei reperibili
    const reperibiliList = document.getElementById('reperibili-list');
    
    // Svuota il container
    reperibiliList.innerHTML = '';
    
    // Se non ci sono turni, mostra un messaggio
    if (turni.length === 0) {
        reperibiliList.innerHTML = '<div class="no-reperibili">Nessun reperibile disponibile per questa data</div>';
    } else {
        // Altrimenti, crea un elemento per ogni turno
        turni.forEach(turno => {
            const reperibile = turno.reperibile;
            const reparto = reperibile.reparto ? reperibile.reparto.nome : 'Nessun reparto';
            
            const turnoElement = document.createElement('div');
            turnoElement.className = 'reperibile-item';
            turnoElement.innerHTML = `
                <div class="reperibile-name">${reperibile.name}</div>
                <div class="reperibile-details">
                    <div><strong>Reparto:</strong> ${reparto}</div>
                    <div><strong>Email:</strong> ${reperibile.email}</div>
                    <div><strong>Telefono:</strong> ${reperibile.phone || 'Non disponibile'}</div>
                    <div><strong>Orario:</strong> ${turno.ora_inizio} - ${turno.ora_fine}</div>
                    ${turno.note ? `<div><strong>Note:</strong> ${turno.note}</div>` : ''}
                </div>
            `;
            
            reperibiliList.appendChild(turnoElement);
        });
    }
    
    // Apri il modal
    const modal = new bootstrap.Modal(document.getElementById('dayDetailsModal'));
    modal.show();
}

// Esegui la funzione quando la pagina è caricata
document.addEventListener('DOMContentLoaded', refreshAtMidnight);