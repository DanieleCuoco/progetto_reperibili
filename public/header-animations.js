document.addEventListener('DOMContentLoaded', function() {
  // ANIMAZIONI HEADER
  // Seleziona gli elementi dell'header da animare
  const header = document.querySelector('header');
  const headerTitle = document.querySelector('.header-content h1');
  const userControls = document.querySelector('.user-controls');
  
  // Aggiungi un piccolo ritardo prima di avviare le animazioni
  setTimeout(() => {
    if (header && header.classList.contains('header-hidden')) {
      header.classList.add('show');
    }
    
    if (headerTitle && headerTitle.classList.contains('header-title-hidden')) {
      headerTitle.classList.add('show');
      // Aggiungi anche la classe per l'effetto di pulsazione
      setTimeout(() => {
        headerTitle.classList.add('animated');
      }, 1500);
    }
    
    if (userControls && userControls.classList.contains('user-controls-hidden')) {
      userControls.classList.add('show');
    }
  }, 300);

  // ANIMAZIONE CONTEGGIO STATISTICHE
  // Funzione per animare il conteggio
  function animateCount(element, target) {
    const duration = 800; // Durata dell'animazione in millisecondi
    const frameDuration = 1000/60; // 60fps
    const totalFrames = Math.round(duration / frameDuration);
    let frame = 0;
    
    const counter = setInterval(() => {
      frame++;
      // Calcolo del valore corrente usando una funzione di easing
      const progress = frame / totalFrames;
      const currentCount = Math.round(easeOutQuad(progress) * target);
      
      element.textContent = currentCount;
      
      if (frame === totalFrames) {
        clearInterval(counter);
        element.textContent = target; // Assicuriamoci che il valore finale sia esatto
      }
    }, frameDuration);
  }
  
  // Funzione di easing per un'animazione più naturale
  function easeOutQuad(t) {
    return t * (2 - t);
  }
  
  // Seleziona tutti gli elementi con classe stat-value
  const statValues = document.querySelectorAll('.stat-value');
  
  // Anima ciascun valore
  statValues.forEach(element => {
    const targetValue = parseInt(element.textContent.trim());
    element.textContent = '0'; // Inizia da zero
    animateCount(element, targetValue);
  });



  
});