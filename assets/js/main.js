(function ($) {
    $(document).ready(function () {
        console.log("Main JS loaded");

        initTabs();
        initCards();
        initModals();

        const isTouchDevice = () => {
            return (
                (typeof window !== 'undefined' &&
                    ('ontouchstart' in window ||
                        (typeof window.DocumentTouch !== 'undefined' &&
                            document instanceof window.DocumentTouch))) ||
                navigator.maxTouchPoints > 0 ||
                navigator.msMaxTouchPoints > 0
            );
        };

        function initModals() {
            const toggleModal = (modal) => {
                modal.toggleClass('opened');
                $('body').toggleClass('noscroll');
            }

            /// Funcionalidad común
            // Cerrar modal clickando en boton
            $('.modal .modal-close').on('click', function (e) {
                e.preventDefault();
                const modal = $(this).closest('.modal');
                
                toggleModal(modal);
            });
            // Cerrar modal clickando fuera del contenido
            $('.modal').on('click', function (e) {
                if ($(e.target).closest('.modal-main').length === 0) {
                    e.preventDefault();
                    toggleModal($(this));
                }
            });

            /// Archive modal (item list)
            // TODO - Logica para abrir modal con contenido correcto
            $('.item-list .link-info').on('click', function (e) {
                e.preventDefault();
                const modal = $('#archive-modal');
                
                toggleModal(modal);
            });
        }

        function initCards() {
            // Detectar si es dispositivo táctil o móvil
            $('.collection-card').on('click', function (e) {
                if (!isTouchDevice() || $(this).hasClass('showing')) return;

                $('.collection-card').not(this).removeClass('showing');
                $(this).toggleClass('showing');
            });
        }

        function initTabs() {
            $('.tabs .tab').on('click', function (e) {
                e.preventDefault();
                const panel = $(this).data('panel');
                const group = $(this).data('group');

                $(`.tabs .tab[data-group="${group}"]`).removeClass('selected');
                $(this).addClass('selected');

                if (!panel) return;
                $(`.content-panel.${group}`).addClass('hidden-panel');
                $(`#${panel}`).removeClass('hidden-panel');
            });
        }
    });
})(jQuery);
