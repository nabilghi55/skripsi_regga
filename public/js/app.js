document.addEventListener('DOMContentLoaded', () => {
    // 1. Splash Screen Transition
    const splash = document.getElementById('splash-screen');
    if (splash) {
        setTimeout(() => {
            splash.style.opacity = '0';
            setTimeout(() => {
                splash.style.display = 'none';
            }, 500);
        }, 1500); // 1.5 seconds splash
    }

    // 2. Character Counter for Textareas
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const counterId = textarea.getAttribute('data-counter');
        const counterSpan = document.getElementById(counterId);
        if (counterSpan) {
            const max = textarea.getAttribute('maxlength');
            // Initial state
            counterSpan.textContent = `${textarea.value.length}/${max}`;
            
            textarea.addEventListener('input', () => {
                counterSpan.textContent = `${textarea.value.length}/${max}`;
            });
        }
    });

    // 3. Modal Close Logic (Mobile View Notification)
    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        const closeBtn = modalOverlay.querySelector('.modal-close');
        const okBtn = modalOverlay.querySelector('.btn-modal-ok');
        
        const closeModal = () => {
            modalOverlay.style.opacity = '0';
            setTimeout(() => {
                modalOverlay.style.display = 'none';
            }, 300);
        };

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (okBtn) okBtn.addEventListener('click', closeModal);
    }
});
