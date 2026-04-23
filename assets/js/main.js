(function ($) {
    $(document).ready(function () {
        console.log("Main JS loaded");

        const tablet = 1024;
        const mobile = 768;

        initTheme();
        initTabs();
        initModals();
        initHero();
        initStudioStickers();

        function openModal(modal) {
            if (!modal.hasClass('opened')) {
                modal.addClass('opened');
                $('body').addClass('noscroll');
            }
        }

        function toggleModal(modal) {
            modal.toggleClass('opened');
            $('body').toggleClass('noscroll');
        }

        function initTheme() {
            if (window.location.pathname === '/') return
            else if (window.location.pathname.includes('/studio')) $('body').addClass('theme--studio');
            else $('body').addClass('theme--artist');
        }

        function initHero() {
            let resizeTimeout;
            let isExiting = false;

            const centerImages = () => {
                // Centrar las imagenes cuando se ha seleccionado un lado
                if ($('.bnomio-hero--half.entered').length > 0) {
                    const hero = $(`.bnomio-hero--half.entered`);
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
                    studioImage.css('opacity', '1');
                    artistImage.css('right', `-${artistImage.width() / 2}px`);
                    artistImage.css('left', 'unset');
                    artistImage.css('opacity', '1');
                }
            }

            const enableHorizontalScroll = (element) => {
                element.addEventListener('wheel', function (e) {
                    if (window.innerWidth <= tablet) return;

                    const absX = Math.abs(e.deltaX);
                    const absY = Math.abs(e.deltaY);

                    // Scroll horizontal (trackpad)
                    if (absX > absY) return;

                    // Comprobar si es scroll vertical
                    const maxScrollLeft = element.scrollWidth - element.clientWidth;
                    const currentScrollLeft = element.scrollLeft;
                    const scrollingToRight = e.deltaY > 0;
                    const scrollingToLeft = e.deltaY < 0;
                    const canScrollRight = currentScrollLeft < maxScrollLeft;
                    const canScrollLeft = currentScrollLeft > 0;

                    if ((scrollingToRight && canScrollRight) || (scrollingToLeft && canScrollLeft)) {
                        e.preventDefault();
                        element.scrollLeft += e.deltaY;
                    }
                }, { passive: false });
            }

            $('.bnomio-hero--half').hover(function () {
                if (window.innerWidth <= tablet || $(this).hasClass('entered') || isExiting) return;

                $('.bnomio-hero--half').removeClass('active');
                $('.bnomio-hero--half').addClass('noactive');
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

                $('.bnomio-hero--half').removeClass('noactive');
                $(this).removeClass('active');

                centerImages();
            })

            $('#enter-studio').on('click', function () {
                const hero = $('.studio-hero');

                $('.bnomio-hero--half').removeClass('active');

                hero.addClass('entered');
                $('body').addClass('studio-entered');
                $('body').addClass('theme--studio');
                document.documentElement.style.overflow = 'hidden';
                document.documentElement.style.height = '100dvh';
                centerImages();

                // Agregar listener para scroll horizontal con rueda
                const element = hero[0];
                enableHorizontalScroll(element);
            })

            $('#enter-artist').on('click', function () {
                // TODO - Coming soon, remove when implemented
                return;
            })


            setTimeout(centerImages, 1000);

            $(window).on('resize', function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(centerImages, 500);
            });

            // TODO - Borrar solo para debug
            $('.exit-button').on('click', function () {
                $('body').removeClass('studio-entered artist-entered');
                $('.bnomio-hero--half').removeClass('entered noactive active');
                document.documentElement.style.overflow = '';
                document.documentElement.style.height = '';
                centerImages();

                isExiting = true;
                setTimeout(() => { isExiting = false }, 1000);
            });
        }

        function initModals() {
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

            generateArchiveModals();
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

        function generateArchiveModals() {
            const modal = $('#archive-modal');
            const casesModalData = window.BINOMIO_CASES_MODAL_DATA || null;

            if (!casesModalData || modal.length === 0) {
                $('.item-list .link-info').on('click', function (e) {
                    e.preventDefault();
                    
                    console.warn("No data for cases modal. Check info in WP admin or contact support.");
                });

                return;
            }

            const modalImage = modal.find('.archive-modal-image');
            const modalTitle = modal.find('.modal-title');
            const modalSubtitle = modal.find('.modal-subtitle');
            const modalDescription = modal.find('.modal-description');
            const modalButtons = modal.find('.modal-buttons');
            const modalPrev = modal.find('.modal-prev');
            const modalNext = modal.find('.modal-next');

            let activePanel = null;
            let activeIndex = 0;

            const renderCaseItem = (panelId, index) => {
                const panelCases = Array.isArray(casesModalData[panelId]) ? casesModalData[panelId] : [];
                if (!panelCases.length) return;

                activePanel = panelId;
                activeIndex = Math.max(0, Math.min(index, panelCases.length - 1));

                const caseItem = panelCases[activeIndex];

                modalTitle.text(caseItem.title || '');
                modalSubtitle.text(caseItem.subtitle || '');
                modalDescription.html(caseItem.content || '');

                if (caseItem.image) {
                    modalImage.attr('src', caseItem.image);
                    modalImage.attr('alt', caseItem.title || '');
                    modalImage.show();
                } else {
                    modalImage.hide();
                }

                modalButtons.empty();
                if (Array.isArray(caseItem.links)) {
                    caseItem.links.forEach((link) => {
                        if (!link || !link.url) return;

                        const button = $('<a></a>')
                            .addClass('button')
                            .attr('href', link.url)
                            .attr('target', '_blank')
                            .attr('rel', 'noopener noreferrer')
                            .text(link.text || 'Ver más');

                        modalButtons.append(button);
                    });
                }

                const hasMultiple = panelCases.length > 1;
                modalPrev.prop('disabled', !hasMultiple);
                modalNext.prop('disabled', !hasMultiple);
            };

            $('.item-list .link-info[data-panel][data-case-index]').on('click', function (e) {
                e.preventDefault();

                const panelId = $(this).data('panel');
                const caseIndex = parseInt($(this).data('case-index'), 10) || 0;

                renderCaseItem(panelId, caseIndex);
                openModal(modal);
            });

            modalPrev.on('click', function (e) {
                e.preventDefault();

                const panelCases = Array.isArray(casesModalData[activePanel]) ? casesModalData[activePanel] : [];
                if (panelCases.length < 2) return;

                const nextIndex = (activeIndex - 1 + panelCases.length) % panelCases.length;
                renderCaseItem(activePanel, nextIndex);
            });

            modalNext.on('click', function (e) {
                e.preventDefault();

                const panelCases = Array.isArray(casesModalData[activePanel]) ? casesModalData[activePanel] : [];
                if (panelCases.length < 2) return;

                const nextIndex = (activeIndex + 1) % panelCases.length;
                renderCaseItem(activePanel, nextIndex);
            });
        }

        function initStudioStickers() {
            const stickersLayer = document.querySelector('.studio-hero .hero-content .studio-stickers');
            if (!stickersLayer) return;

            const stickers = Array.from(stickersLayer.querySelectorAll('.studio-sticker'));
            if (stickers.length === 0) return;

            let zIndexCounter = stickers.reduce((maxZ, sticker) => {
                const currentZ = parseInt(window.getComputedStyle(sticker).zIndex, 10);
                return Number.isNaN(currentZ) ? maxZ : Math.max(maxZ, currentZ);
            }, 10);

            const placeSticker = (sticker, clientX, clientY, pointerOffsetX, pointerOffsetY) => {
                const layerRect = stickersLayer.getBoundingClientRect();
                const nextLeft = clientX - layerRect.left - pointerOffsetX;
                const nextTop = clientY - layerRect.top - pointerOffsetY;
                const maxLeft = Math.max(0, layerRect.width - sticker.offsetWidth);
                const maxTop = Math.max(0, layerRect.height - sticker.offsetHeight);

                const boundedLeft = Math.min(Math.max(0, nextLeft), maxLeft);
                const boundedTop = Math.min(Math.max(0, nextTop), maxTop);

                sticker.style.left = `${boundedLeft}px`;
                sticker.style.top = `${boundedTop}px`;
                sticker.style.setProperty('--sticker-x', 'unset');
                sticker.style.setProperty('--sticker-y', 'unset');
            };

            stickers.forEach((sticker) => {
                sticker.style.touchAction = 'none';

                sticker.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0 && event.pointerType === 'mouse') return;

                    event.preventDefault();

                    // Lock current CSS position to px (offsetLeft/Top ignores transforms)
                    const currentLeft = sticker.offsetLeft;
                    const currentTop = sticker.offsetTop;
                    sticker.style.left = `${currentLeft}px`;
                    sticker.style.top = `${currentTop}px`;
                    sticker.style.setProperty('--sticker-x', 'unset');
                    sticker.style.setProperty('--sticker-y', 'unset');

                    zIndexCounter += 1;
                    sticker.style.zIndex = String(zIndexCounter);
                    sticker.classList.add('is-dragging');

                    // Pointer offset relative to CSS position, not visual bounding box
                    const layerRect = stickersLayer.getBoundingClientRect();
                    const pointerOffsetX = event.clientX - layerRect.left - currentLeft;
                    const pointerOffsetY = event.clientY - layerRect.top - currentTop;

                    const onPointerMove = (moveEvent) => {
                        placeSticker(sticker, moveEvent.clientX, moveEvent.clientY, pointerOffsetX, pointerOffsetY);
                    };

                    const stopDragging = () => {
                        sticker.classList.remove('is-dragging');
                        sticker.releasePointerCapture(event.pointerId);
                        sticker.removeEventListener('pointermove', onPointerMove);
                        sticker.removeEventListener('pointerup', stopDragging);
                        sticker.removeEventListener('pointercancel', stopDragging);
                    };

                    sticker.setPointerCapture(event.pointerId);
                    sticker.addEventListener('pointermove', onPointerMove);
                    sticker.addEventListener('pointerup', stopDragging);
                    sticker.addEventListener('pointercancel', stopDragging);
                });
            });
        }
    });
})(jQuery);
