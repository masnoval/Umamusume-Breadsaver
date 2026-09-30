document.addEventListener('DOMContentLoaded', () => {

    document
        .querySelectorAll('[data-confirm]')
        .forEach((form) => {

            form.addEventListener('submit', (event) => {

                const message =
                    form.getAttribute('data-confirm') ||
                    'Yakin ingin melanjutkan?';

                if (!window.confirm(message)) {
                    event.preventDefault();
                }

            });

        });


    document
        .querySelectorAll('.alert')
        .forEach((alert) => {

            setTimeout(() => {

                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-5px)';
                alert.style.transition =
                    'opacity .3s ease, transform .3s ease';

                setTimeout(() => {
                    alert.remove();
                }, 300);

            }, 4500);

        });

});
