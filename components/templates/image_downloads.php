<?php
/**
 * Image Downloads Section
 */

$section_title = isset($component['section_title']) ? (string) $component['section_title'] : '';
$section_description = isset($component['section_description']) ? (string) $component['section_description'] : '';
$gallery_items = isset($component['gallery_items']) && is_array($component['gallery_items']) ? $component['gallery_items'] : array();

// Compatibilidad con contenido previo (1 sola imagen + lista de formatos)
if (empty($gallery_items) && (!empty($component['preview_image']) || !empty($component['download_formats']))) {
    $gallery_items = array(
        array(
            'item_title' => $section_title,
            'preview_image' => isset($component['preview_image']) ? (int) $component['preview_image'] : 0,
            'item_description' => '',
            'download_formats' => isset($component['download_formats']) && is_array($component['download_formats']) ? $component['download_formats'] : array(),
        ),
    );
}
?>

<section class="section-image-downloads">
    <div class="container">
        <?php if ($section_title !== '') : ?>
            <h2 class="section-title text-h2"><?php echo esc_html($section_title); ?></h2>
        <?php endif; ?>

        <?php if ($section_description !== '') : ?>
            <div class="section-description body-small"><?php echo wp_kses_post($section_description); ?></div>
        <?php endif; ?>

        <div class="image-downloads-gallery">
        <?php foreach ($gallery_items as $gallery_item) : ?>
            <?php
            $item_title = isset($gallery_item['item_title']) ? (string) $gallery_item['item_title'] : '';
            $preview_image = isset($gallery_item['preview_image']) ? (int) $gallery_item['preview_image'] : 0;
            $download_formats = isset($gallery_item['download_formats']) && is_array($gallery_item['download_formats']) ? $gallery_item['download_formats'] : array();

            if ($preview_image <= 0 && empty($download_formats)) {
                continue;
            }
            ?>
            <article class="image-downloads-card">
                <div class="card-image">
                    <?php if ($preview_image > 0) : ?>
                        <img src="<?php echo esc_url(wp_get_attachment_url($preview_image)); ?>" alt="<?php echo esc_attr($item_title !== '' ? $item_title : 'Preview image'); ?>">
                    <?php endif; ?>
                </div>
                <?php if (!empty($download_formats)) : ?>
                    <div class="card-formats">
                        <?php foreach ($download_formats as $item) : ?>
                            <?php
                            $format_label = isset($item['format_label']) ? (string) $item['format_label'] : '';
                            $format_file_id = isset($item['format_file']) ? (int) $item['format_file'] : 0;
                            $format_file_url = $format_file_id > 0 ? wp_get_attachment_url($format_file_id) : '';

                            if ($format_file_url === '') {
                                continue;
                            }

                            $format_label = $format_label !== '' ? $format_label : strtoupper((string) pathinfo((string) $format_file_url, PATHINFO_EXTENSION));
                            ?>
                            <a class="format-button" href="<?php echo esc_url($format_file_url); ?>" download title="<?php echo esc_attr($format_label); ?>">
                                <?php echo esc_html($format_label); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        </div>
    </div>
</section>
