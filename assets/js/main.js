(function ($) {
    $(document).ready(function () {
        console.log("Main JS loaded");

        initTabs();
        initCards();

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
                $('.tabs .tab').removeClass('selected');
                $(this).addClass('selected');

                const panel = $(this).data('panel');
                if (!panel) return;
                $('.content-panel').addClass('hidden-panel');
                $(`#${panel}`).removeClass('hidden-panel');
            });
        }
    });
})(jQuery);
