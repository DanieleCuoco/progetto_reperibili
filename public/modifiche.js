document.addEventListener('DOMContentLoaded', function() {
    // Gestione delle notifiche
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllReadBtn = document.getElementById('markAllRead');
    
    // Toggle del dropdown delle notifiche
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('show');
    });
    
    // Chiudi il dropdown quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
            notificationDropdown.classList.remove('show');
        }
    });
    
    // Segna tutte le notifiche come lette
    markAllReadBtn.addEventListener('click', function() {
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(notification => {
            notification.classList.remove('unread');
        });
        notificationBadge.textContent = '0';
        notificationBadge.style.display = 'none';
    });
    
    // Gestione dei pulsanti di approvazione e rifiuto nelle notifiche
    const approveButtons = document.querySelectorAll('.notification-actions .btn-approve');
    const rejectButtons = document.querySelectorAll('.notification-actions .btn-reject');
    
    approveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const notificationItem = this.closest('.notification-item');
            notificationItem.style.backgroundColor = 'rgba(46, 204, 113, 0.2)';
            setTimeout(() => {
                notificationItem.remove();
                updateNotificationBadge();
            }, 500);
        });
    });
    
    rejectButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const notificationItem = this.closest('.notification-item');
            notificationItem.style.backgroundColor = 'rgba(231, 76, 60, 0.2)';
            setTimeout(() => {
                notificationItem.remove();
                updateNotificationBadge();
            }, 500);
        });
    });
    
    function updateNotificationBadge() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        notificationBadge.textContent = unreadCount;
        
        if (unreadCount === 0) {
            notificationBadge.style.display = 'none';
        } else {
            notificationBadge.style.display = 'flex';
        }
    }
    
    // Gestione delle tab per le modifiche
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Rimuovi la classe active da tutti i pulsanti
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Aggiungi la classe active al pulsante cliccato
            this.classList.add('active');
            
            // Nascondi tutti i contenuti delle tab
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Mostra il contenuto della tab selezionata
            const tabId = this.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
    
    // Gestione dei pulsanti di approvazione e rifiuto nelle modifiche
    const modifyApproveButtons = document.querySelectorAll('.modifica-actions .btn-approve');
    const modifyRejectButtons = document.querySelectorAll('.modifica-actions .btn-reject');
    
    modifyApproveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modificaCard = this.closest('.modifica-card');
            const modificaActions = modificaCard.querySelector('.modifica-actions');
            
            // Crea l'elemento di stato approvato
            const approvedStatus = document.createElement('div');
            approvedStatus.className = 'modifica-status approved';
            approvedStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Approvato';
            
            // Sostituisci le azioni con lo stato
            modificaActions.replaceWith(approvedStatus);
            
            // Sposta la card nella tab "Approvate"
            setTimeout(() => {
                const approvedTab = document.getElementById('approved-tab');
                const approvedList = approvedTab.querySelector('.modifiche-list');
                modificaCard.remove();
                approvedList.prepend(modificaCard);
                
                // Aggiorna il contatore delle modifiche in attesa
                updatePendingCount();
            }, 1000);
        });
    });
    
    modifyRejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modificaCard = this.closest('.modifica-card');
            const modificaActions = modificaCard.querySelector('.modifica-actions');
            
            // Crea l'elemento di stato rifiutato
            const rejectedStatus = document.createElement('div');
            rejectedStatus.className = 'modifica-status rejected';
            rejectedStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i> Rifiutato';
            
            // Sostituisci le azioni con lo stato
            modificaActions.replaceWith(rejectedStatus);
            
            // Sposta la card nella tab "Rifiutate"
            setTimeout(() => {
                const rejectedTab = document.getElementById('rejected-tab');
                const rejectedList = rejectedTab.querySelector('.modifiche-list');
                modificaCard.remove();
                rejectedList.prepend(modificaCard);
                
                // Aggiorna il contatore delle modifiche in attesa
                updatePendingCount();
            }, 1000);
        });
    });
    
    function updatePendingCount() {
        const pendingCount = document.querySelectorAll('#pending-tab .modifica-card').length;
        document.querySelector('.tab-btn[data-tab="pending"]').textContent = `In attesa (${pendingCount})`;
        
        // Aggiorna anche il contatore nella dashboard
        const modificheInAttesaCounter = document.querySelector('.stat-value:last-child');
        if (modificheInAttesaCounter) {
            modificheInAttesaCounter.textContent = pendingCount;
        }
    }
    
    // Effetto di animazione per le card
    const modificaCards = document.querySelectorAll('.modifica-card');
    modificaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.3), 0 0 0 1px var(--primary-color)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Gestione pulsanti di approvazione
    const approveBtns = document.querySelectorAll('.approve-btn');
    approveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const turnoId = this.closest('.modifica-card').dataset.turnoId;
            if (confirm('Sei sicuro di voler approvare questa richiesta?')) {
                // Invia richiesta di approvazione
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/modifiche/approva/${turnoId}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Gestione pulsanti di rifiuto
    const rejectBtns = document.querySelectorAll('.reject-btn');
    rejectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const turnoId = this.closest('.modifica-card').dataset.turnoId;
            if (confirm('Sei sicuro di voler rifiutare questa richiesta?')) {
                // Invia richiesta di rifiuto
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/modifiche/rifiuta/${turnoId}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

// Script per la pagina delle modifiche
document.addEventListener('DOMContentLoaded', function() {
    // Inizializzazione della pagina
    console.log('Pagina modifiche caricata');
    
    // Gestione hover sulle card
    const cards = document.querySelectorAll('.card');
    if (cards.length > 0) {
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('card-hover');
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('card-hover');
            });
        });
    }
});
