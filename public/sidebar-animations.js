// ===== SIDEBAR ANIMATIONS SPETTACOLARI =====

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Inizializzazione sidebar moderna...');
    
    // Elementi della sidebar
    const sidebar = document.querySelector('.sidebar');
    const sidebarHeader = document.querySelector('.sidebar-header');
    const menuItems = document.querySelectorAll('.sidebar-menu li');
    
    if (!sidebar) {
        console.log('❌ Sidebar non trovata');
        return;
    }
    
    // ANIMAZIONE DI ENTRATA SPETTACOLARE
    function initSidebarAnimations() {
        // Delay iniziale per permettere al DOM di caricarsi
        setTimeout(() => {
            // Anima la sidebar principale
            sidebar.classList.add('sidebar-loaded');
            console.log('✨ Sidebar caricata con dissolvenza');
            
            // Anima l'header con delay
            setTimeout(() => {
                if (sidebarHeader) {
                    sidebarHeader.classList.add('header-loaded');
                    console.log('📋 Header sidebar animato');
                }
            }, 200);
            
            // Anima gli elementi del menu con delay progressivo
            menuItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('menu-item-loaded');
                    console.log(`🔗 Menu item ${index + 1} animato`);
                }, 300 + (index * 100));
            });
            
        }, 100);
    }
    
    // EFFETTI HOVER AVANZATI
    function setupAdvancedHoverEffects() {
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            if (!link) return;
            
            // Effetto ripple al click
            link.addEventListener('click', function(e) {
                // Crea l'effetto ripple
                const ripple = document.createElement('div');
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(0, 191, 255, 0.3);
                    transform: scale(0);
                    animation: ripple-effect 0.6s linear;
                    pointer-events: none;
                    z-index: 1000;
                `;
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                this.appendChild(ripple);
                
                // Rimuovi il ripple dopo l'animazione
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);
            });
            
            // Effetto hover con particelle
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px) scale(1.02)';
                
                // Aggiungi un leggero shake all'icona
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.animation = 'icon-shake 0.5s ease-in-out';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = '';
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.animation = '';
                }
            });
        });
    }
    
    // GESTIONE MENU ATTIVO
    function updateActiveMenu() {
        const currentPath = window.location.pathname;
        
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            if (!link) return;
            
            item.classList.remove('active');
            
            // Controlla se il link corrisponde alla pagina attuale
            const href = link.getAttribute('href');
            if (href && (href === currentPath || currentPath.includes(href))) {
                item.classList.add('active');
                console.log('🎯 Menu attivo aggiornato:', href);
            }
        });
    }
    
    // SCROLL FLUIDO SENZA STACCHI
    function setupSmoothScrolling() {
        sidebar.addEventListener('scroll', function() {
            // Calcola la posizione dello scroll
            const scrollTop = this.scrollTop;
            const scrollHeight = this.scrollHeight - this.clientHeight;
            const scrollPercent = scrollTop / scrollHeight;
            
            // Applica un effetto di dissolvenza graduale durante lo scroll
            const opacity = Math.max(0.85, 1 - (scrollPercent * 0.15));
            this.style.background = `linear-gradient(180deg, 
                rgba(15, 15, 15, ${opacity}) 0%,
                rgba(20, 20, 20, ${opacity - 0.03}) 20%,
                rgba(25, 25, 25, ${opacity - 0.06}) 40%,
                rgba(30, 30, 30, ${opacity - 0.09}) 60%,
                rgba(35, 35, 35, ${opacity - 0.12}) 80%,
                rgba(40, 40, 40, ${opacity - 0.15}) 100%)`;
        });
    }
    
    // RESPONSIVE MOBILE
    function setupMobileResponsive() {
        if (window.innerWidth <= 768) {
            console.log('📱 Modalità mobile attivata');
            
            // Aggiungi un toggle per mobile se necessario
            const menuToggle = document.querySelector('.menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-loaded');
                });
            }
        }
    }
    
    // INIZIALIZZA TUTTO
    initSidebarAnimations();
    setupAdvancedHoverEffects();
    updateActiveMenu();
    setupSmoothScrolling();
    setupMobileResponsive();
    
    console.log('🚀 Sidebar moderna completamente inizializzata!');
});

// CSS per l'animazione ripple
const rippleCSS = `
@keyframes ripple-effect {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes icon-shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px) rotate(-2deg); }
    75% { transform: translateX(2px) rotate(2deg); }
}
`;

// Aggiungi il CSS al documento
const style = document.createElement('style');
style.textContent = rippleCSS;
document.head.appendChild(style);