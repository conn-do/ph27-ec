document.querySelectorAll('form[data-submit]').forEach((form) => {
    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        form.setAttribute('aria-busy', 'true');
        form.querySelectorAll('button[type="submit"]').forEach((button) => {
            button.dataset.originalLabel = button.textContent;
            button.disabled = true;
            button.textContent = '処理中…';
        });
    });
});
window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-original-label]').forEach((button) => {
        button.textContent = button.dataset.originalLabel;
        button.disabled = false;
        button.closest('form')?.removeAttribute('aria-busy');
    });
});
