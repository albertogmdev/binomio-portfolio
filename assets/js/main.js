(function ($) {
    $(document).ready(function () {
        console.log("Main JS loaded");

        const tablet = 1024;
        const mobile = 768;

        initTabs();
        initCards();
        initModals();
        initHero();

        function initHero() {
            let resizeTimeout;
            let isExiting = false;

            const centerImages = () => {
                console.log('Centering images');
                // Centrar las imagenes cuando se ha seleccionado un lado
                if ($('.binomio-hero--half.entered').length > 0) {
                    const hero = $(`.binomio-hero--half.entered`);
                    const heroImage = hero.find('.hero-image');

                    // Desktop > izquierda, Mobile > centro
                    heroImage.css('left', window.innerWidth <= tablet ?
                        `calc(50% - ${heroImage.width() / 2}px)`
                        :
                        '0'
                    );
                    heroImage.css('right', 'unset');
                }
                else {
                    const studioImage = $('.studio-hero .hero-image')
                    const artistImage = $('.artist-hero .hero-image')

                    studioImage.css('left', `-${studioImage.width() / 2}px`);
                    studioImage.css('right', 'unset');
                    artistImage.css('right', `-${artistImage.width() / 2}px`);
                    artistImage.css('left', 'unset');
                }
            }

            $('.binomio-hero--half').hover(function () {
                if (window.innerWidth <= tablet || $(this).hasClass('entered') || isExiting) return;

                $('.binomio-hero--half').removeClass('active');
                $('.binomio-hero--half').addClass('noactive');
                $(this).removeClass('noactive');
                $(this).addClass('active');

                const studioImage = $('.studio-hero .hero-image')
                const artistImage = $('.artist-hero .hero-image')
                if ($(this).hasClass('studio-hero')) {
                    studioImage.css('left', `calc(33% - ${$(studioImage).width() / 2}px)`);
                    studioImage.css('right', 'unset');
                    artistImage.css('right', `calc(-${$(artistImage).width()}px)`);
                    artistImage.css('left', 'unset');

                } else {
                    artistImage.css('right', `calc(33% - ${$(artistImage).width() / 2}px)`);
                    artistImage.css('left', 'unset');
                    studioImage.css('left', `calc(-${$(studioImage).width()}px)`);
                    studioImage.css('right', 'unset');
                }
            }, function () {
                if (window.innerWidth <= tablet || $(this).hasClass('entered') || isExiting) return;

                $('.binomio-hero--half').removeClass('noactive');
                $(this).removeClass('active');

                centerImages();
            })

            $('#enter-studio').on('click', function () {
                const hero = $('.studio-hero');

                $('.binomio-hero--half').removeClass('active');

                hero.addClass('entered');
                $('body').addClass('studio-entered');
                centerImages();

                // Agregar listener para scroll horizontal con rueda
                const enteredElement = hero[0];
                enteredElement.addEventListener('wheel', function (e) {
                    if (window.innerWidth <= tablet) return;

                    e.preventDefault();
                    enteredElement.scrollLeft += e.deltaY;
                }, { passive: false });
            })

            $('#enter-artist').on('click', function () {
                // TODO - Coming soon, remove when implemented
                return;
            })
            

            setTimeout(centerImages, 500);

            $(window).on('resize', function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(centerImages, 500);
            });

            // TODO - Borrar solo para debug
            $('.exit-button').on('click', function () {
                $('body').removeClass('studio-entered artist-entered');
                $('.binomio-hero--half').removeClass('entered noactive active');
                centerImages();

                isExiting = true;
                setTimeout(() => { isExiting = false }, 1000);
            });
        }

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
