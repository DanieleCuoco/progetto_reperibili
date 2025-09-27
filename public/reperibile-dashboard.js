function editTurno(id, dataInizio, dataFine, oraInizio, oraFine, note) {
    // Popola il form con i dati del turno
    document.getElementById('turnoId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('data_inizio').value = dataInizio;
    document.getElementById('data_fine').value = dataFine;
    document.getElementById('ora_inizio').value = oraInizio;
    document.getElementById('ora_fine').value = oraFine;
    document.getElementById('note').value = note || '';
    
    // Cambia l'action del form
    const baseUrl = window.location.origin + '/reperibile/turni/';
    document.getElementById('turnoForm').action = baseUrl + id;
    
    // Cambia il titolo e il testo del bottone
    document.getElementById('formTitle').textContent = 'Modifica turno';
    document.getElementById('submitBtn').textContent = 'Aggiorna turno';
    document.getElementById('cancelBtn').style.display = 'inline-block';
    
    // Scrolla al form
    document.getElementById('turnoForm').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    // Reset del form
    document.getElementById('turnoForm').reset();
    document.getElementById('turnoId').value = '';
    document.getElementById('formMethod').value = '';
    
    // Ripristina l'action originale
    const storeUrl = window.location.origin + '/reperibile/turni';
    document.getElementById('turnoForm').action = storeUrl;
    
    document.getElementById('formTitle').textContent = 'Inserisci nuovo turno';
    document.getElementById('submitBtn').textContent = 'Salva turno';
    document.getElementById('cancelBtn').style.display = 'none';
}