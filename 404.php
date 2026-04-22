<?php

/**
 * 404 template
 */

get_header();

$home_url = home_url('/');
?>

<div class="page-404">
    <section class="section-hero">
        <div class="container">
            <div class="decoration-row">
                <span class="decoration decoration--topleft"></span>
                <span class="decoration decoration--topright"></span>
            </div>
            <div class="content-row">
                <div class="content-item content-topleft">
                    <h1 class="text-h1">404</h1>
                    <h2 class="text-h3"><?php echo esc_html(bnm_t('404_subtitle', 'Page not found')); ?></h2>
                </div>
                <div class="content-item content-topright">
                    <p class="body-small"><?php echo esc_html(bnm_t('404_desc', 'The page you are looking for does not exist or has been moved.')); ?></p>
                    <a href="<?php echo esc_url($home_url); ?>" class="btn"><?php echo esc_html(bnm_t('404_cta', 'Back to home')); ?></a>
                </div>
            </div>
            <div class="decoration-row">
                <span class="decoration decoration--bottomleft"></span>
                <span class="decoration decoration--bottomright"></span>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>