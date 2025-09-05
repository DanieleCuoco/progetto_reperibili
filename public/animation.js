document.addEventListener('DOMContentLoaded', function() {
  // Seleziona tutti gli elementi con classi di animazione
  const hiddenElements = document.querySelectorAll('.hidden, .hidden-left, .hidden-right, .hidden-down');
  
  
  // Aggiungi un piccolo ritardo prima di avviare le animazioni
  setTimeout(() => {
    hiddenElements.forEach(el => el.classList.add('show'));
  }, 500);

//   // Animazione per gli alert di successo
//   const alerts = document.querySelectorAll('.alert.alert-success');
//   alerts.forEach(alert => {
//     // Aggiungi la classe hidden se non è già presente
//     if (!alert.classList.contains('hidden')) {
//       alert.classList.add('hidden');
//     }
    
//     // Dopo un breve ritardo, mostra l'alert con l'animazione
//     setTimeout(() => {
//       alert.classList.add('show');
//     }, 500);
//   });
  
  
});

