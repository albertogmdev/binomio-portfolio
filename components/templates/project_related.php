<?php
/**
 * Project Related Component Template
 */

$related = isset($component['project_related_items']) && is_array($component['project_related_items'])
    ? $component['project_related_items']
    : array();

if (empty($related)) {
    return;
}

$post_language = function_exists('binomio_get_post_language') ? binomio_get_post_language(get_the_ID()) : '';
?>

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

                $related_title     = get_the_title($related_id);
                $related_permalink = get_permalink($related_id);

                $related_featured_image_id = carbon_get_post_meta($related_id, 'proyecto_featured_image');
                $related_cover_image_id    = carbon_get_post_meta($related_id, 'proyecto_portada');
                $related_image_id          = !empty($related_featured_image_id) ? $related_featured_image_id : $related_cover_image_id;

                $related_image_url = !empty($related_image_id) ? wp_get_attachment_url($related_image_id) : '';

                if (empty($related_permalink)) {
                    continue;
                }
                ?>
                <a
                    href="<?php echo esc_url($related_permalink); ?>"
                    class="related-item">
                    <?php if (!empty($related_image_url)) : ?>
                        <img
                            src="<?php echo esc_url($related_image_url); ?>"
                            alt="<?php echo esc_attr($related_title); ?> portada"
                            class="related-image" />
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
