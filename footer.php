</main>
</div>
<?php if (!is_front_page()) : ?>
    <?php
    $studio_url = function_exists('binomio_get_localized_page_url')
        ? binomio_get_localized_page_url(
            array(
                'es' => array('estudio'),
                'en' => array('studio'),
            ),
            '/estudio/'
        )
        : home_url('/estudio/');
    $artist_url = function_exists('binomio_get_localized_page_url')
        ? binomio_get_localized_page_url(
            array(
                'es' => array('artistas'),
                'en' => array('artists', 'collections'),
            ),
            '/artistas/'
        )
        : home_url('/artistas/');
    $language_items = function_exists('binomio_get_language_switcher_items')
        ? binomio_get_language_switcher_items()
        : array();
    ?>
    <footer id="footer" class="" role="contentinfo">
        <div class="footer-main">
            <div class="footer-socials">
                <a href="https://www.instagram.com/bnomio" target="_blank" class="social-link">
                    <span class="icon icon-instagram"></span>
                    <p class="social-text">@BNOMIO</p>
                </a>
            </div>
            <div class="footer-info">
                <p><?php echo esc_html__('BNOMIO | COPYRIGHT 2025 ALL RIGHTS RESERVED', 'binomio'); ?></p>
            </div>
        </div>
        <div class="footer-navigation theme--artist">
            <ul class="navigation-left">
                <li><a class="link <?= is_studio() ? 'link--active' : '' ?>" href="<?php echo esc_url($studio_url); ?>"><?php echo esc_html__('Studio zone', 'binomio'); ?></a></li>
                <li><a class="link <?= is_artist() ? 'link--active' : '' ?>" href="<?php echo esc_url($artist_url); ?>"><?php echo esc_html__('Artist zone', 'binomio'); ?></a></li>
            </ul>
            <ul class="navigation-right">
                <?php foreach ($language_items as $language_item) : ?>
                    <li>
                        <a
                            class="link <?= !empty($language_item['current']) ? 'link--active' : '' ?>"
                            href="<?php echo esc_url(!empty($language_item['url']) ? $language_item['url'] : '#'); ?>">
                            <?php echo esc_html($language_item['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </footer>
<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>

</html>