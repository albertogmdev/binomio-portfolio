<?php

/**
 * Single template para CPT Proyectos
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $post_language = function_exists('binomio_get_post_language') ? binomio_get_post_language($post_id) : '';

        $featured_home = (bool) carbon_get_post_meta($post_id, 'proyecto_featured_home');
        $featured_image = carbon_get_post_meta($post_id, 'proyecto_featured_image');

        $titulo = get_the_title($post_id);
        $subtitulo = tcf_meta($post_id, 'proyecto_subtitulo', 'proyecto_translations');
        $descripcion = tcf_meta($post_id, 'proyecto_descripcion', 'proyecto_translations');
        $links = carbon_get_post_meta($post_id, 'proyecto_links');
        $tags = carbon_get_post_meta($post_id, 'proyecto_tags');
        $portada = carbon_get_post_meta($post_id, 'proyecto_portada');
        $full_gallery = carbon_get_post_meta($post_id, 'proyecto_full_assets');
        $gallery = carbon_get_post_meta($post_id, 'proyecto_galeria_assets');
        $creditos = tcf_meta($post_id, 'proyecto_creditos', 'proyecto_translations');
        $related = carbon_get_post_meta($post_id, 'proyecto_related');
    endwhile;
endif;
?>

<div id="project-<?= esc_attr($post_id) ?>" class="page-project">
    <section class="section-hero">
        <div class="container">
            <div class="decoration-row">
                <span class="decoration decoration--topleft"></span>
                <span class="decoration decoration--topright"></span>
            </div>
            <div class="content-row">
                <div class="content-item content-topleft">
                    <h1 class="text-h1"><?= esc_html($titulo) ?></h1>
                    <?php if (!empty($subtitulo)) : ?>
                        <h2 class="text-h3"><?= esc_html($subtitulo) ?></h2>
                    <?php endif; ?>
                </div>
                <div class="content-item content-topright">
                    <p class="body-small"><?= $descripcion ?></p>
                </div>
            </div>
            <div class="decoration-row">
                <span class="decoration decoration--midleft"></span>
                <span class="decoration decoration--midright"></span>
            </div>
            <div class="content-row">
                <div class="content-item content-item--empty content-bottomleft"></div>
                <div class="content-item content-bottomright">
                    <div class="tag-container">
                        <?php if (!empty($tags)) : ?>
                            <?php foreach ($tags as $tag) : ?>
                                <div class="tag"><?= esc_html($tag) ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="link-container">
                        <?php if (!empty($links)) : ?>
                            <?php foreach ($links as $link) : ?>
                                <?php
                                $link_text = tcf_item($link, 'texto');
                                $raw_url = $link['url'] ?? '';
                                if ($raw_url !== '' && !preg_match('/^(?:[a-z][a-z0-9+\-.]*:|\/\/|#|\?)/i', $raw_url)) {
                                    $raw_url = 'https://' . $raw_url;
                                }
                                $link_url = tcf_url($raw_url);

                                if ($link_url === '') {
                                    continue;
                                }
                                if (!preg_match('#^https?://#i', $link_url)) {
                                    $link_url = 'https://' . $link_url;
                                }
                                ?>
                                <a
                                    href="<?= esc_url($link_url) ?>"
                                    class="button button--secondary button-icon"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    <span class="text"><?= esc_html($link_text) ?></span>
                                    <span class="icon icon-link"></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="decoration-row">
                <span class="decoration decoration--bottomleft"></span>
                <span class="decoration decoration--bottomright"></span>
            </div>
        </div>
    </section>
    <?php if (!empty($portada)) : ?>
        <section class="section-frontimage">
            <div class="container">
                <img
                    src="<?= esc_url(wp_get_attachment_url($portada)) ?>"
                    alt="<?= esc_attr($titulo) ?> portada"
                    class="frontimage" />
            </div>
        </section>
    <?php endif; ?>
    <?php if (is_array($full_gallery) && !empty($full_gallery)) : ?>
        <section class="section-fullgallery">
            <div class="gallery-grid">
                <?php foreach ($full_gallery as $asset_id) : ?>
                    <?php
                    $asset_mime = get_post_mime_type($asset_id);
                    $asset_url = wp_get_attachment_url($asset_id);
                    if (empty($asset_url)) continue;
                    ?>
                    <?php if (!empty($asset_mime) && strpos($asset_mime, 'video/') === 0) : ?>
                        <video
                            class="gallery-item"
                            playsinline
                            controls
                            controlslist="nodownload nofullscreen noremoteplayback"
                            preload="metadata">
                            <source
                                src="<?= esc_url($asset_url) ?>"
                                type="<?= esc_attr($asset_mime) ?>">
                        </video>
                    <?php else : ?>
                        <img
                            src="<?= esc_url($asset_url) ?>"
                            alt="<?= esc_attr($titulo) ?> asset"
                            class="gallery-item" />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    <?php if (is_array($gallery) && !empty($gallery)) : ?>
        <section class="section-gallery">
            <div class="container">
                <div class="gallery-grid">
                    <?php foreach ($gallery as $asset_id) : ?>
                        <?php
                        $asset_mime = get_post_mime_type($asset_id);
                        $asset_url = wp_get_attachment_url($asset_id);
                        if (empty($asset_url)) continue;
                        ?>
                        <div class="gallery-item gallery-item--<?= !empty($asset_mime) && strpos($asset_mime, 'video/') === 0 ? 'video' : 'image' ?>">
                            <?php if (!empty($asset_mime) && strpos($asset_mime, 'video/') === 0) : ?>
                                <video
                                    class="image-item"
                                    playsinline
                                    controls
                                    controlslist="nodownload nofullscreen noremoteplayback"
                                    preload="metadata">
                                    <source
                                        src="<?= esc_url($asset_url) ?>"
                                        type="<?= esc_attr($asset_mime) ?>">
                                </video>
                            <?php else : ?>
                                <img
                                    src="<?= esc_url($asset_url) ?>"
                                    alt="<?= esc_attr($titulo) ?> asset"
                                    class="image-item" />
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php if (!empty($creditos)) : ?>
        <section class="section-credits">
            <div class="container">
                <div class="tag-container">
                    <?php if (!empty($tags)) : ?>
                        <?php foreach ($tags as $tag) : ?>
                            <div class="tag"><?= esc_html($tag) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p class="credits-title body-large">[ <?php echo esc_html(bnm_t('single_credits', 'CREDITS')); ?> ]</p>
                <div class="credits-content body-small">
                    <?= $creditos ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php if (is_array($related) && !empty($related)) : ?>
        <section class="section-related">
            <div class="container">
                <p class="related-title text-h3">[ <?php echo esc_html(bnm_t('single_other', 'OTHER PROJECTS')); ?> ]</p>
                <div class="related-grid">
                    <?php foreach ($related as $related_item) : ?>
                        <?php
                        $related_id = 0;

                        if (is_array($related_item) && isset($related_item['id'])) {
                            $related_id = (int) $related_item['id'];
                        } elseif (is_numeric($related_item)) {
                            $related_id = (int) $related_item;
                        }

                        if ($related_id <= 0) {
                            continue;
                        }

                        if (function_exists('binomio_get_translated_post_id')) {
                            $related_id = binomio_get_translated_post_id($related_id, $post_language);
                        }

                        if ($related_id <= 0 || get_post_type($related_id) !== 'projects') {
                            continue;
                        }

                        $related_title = get_the_title($related_id);
                        $related_permalink = get_permalink($related_id);

                        $related_featured_image_id = carbon_get_post_meta($related_id, 'proyecto_featured_image');
                        $related_cover_image_id = carbon_get_post_meta($related_id, 'proyecto_portada');
                        $related_image_id = !empty($related_featured_image_id) ? $related_featured_image_id : $related_cover_image_id;

                        $related_image_url = !empty($related_image_id) ? wp_get_attachment_url($related_image_id) : '';

                        if (empty($related_permalink)) {
                            continue;
                        }
                        ?>
                        <a
                            href="<?= esc_url($related_permalink) ?>"
                            class="related-item">
                            <?php if (!empty($related_image_url)) : ?>
                                <img
                                    src="<?= esc_url($related_image_url) ?>"
                                    alt="<?= esc_attr($related_title) ?> portada"
                                    class="related-image" />
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
?>