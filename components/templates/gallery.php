<?php
/**
 * Gallery Component Template
 */

$items = isset($component['gallery_items']) && is_array($component['gallery_items'])
    ? $component['gallery_items']
    : array();

if (empty($items)) {
    return;
}

$remove_gap    = !empty($component['gallery_remove_column_space']);
$titulo        = get_the_title();
$section_class = 'section-gallery' . ($remove_gap ? ' section-gallery--nogap' : '');
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="gallery-grid">
            <?php foreach ($items as $item) : ?>
                <?php
                $asset_id     = isset($item['asset_id']) ? (int) $item['asset_id'] : 0;
                $is_fullwidth = !empty($item['asset_fullwidth']);
                $loop_video   = !empty($item['asset_loop_video']);
                if (empty($asset_id)) {
                    continue;
                }
                $asset_mime = get_post_mime_type($asset_id);
                $asset_url  = wp_get_attachment_url($asset_id);
                if (empty($asset_url)) {
                    continue;
                }
                $is_video   = !empty($asset_mime) && strpos($asset_mime, 'video/') === 0;
                $type_class = $is_video ? 'video' : (strpos($asset_mime, 'image/gif') === 0 ? 'gif' : 'image');
                $fw_class   = $is_fullwidth ? ' gallery-item--full' : '';
                ?>
                <div class="gallery-item gallery-item--<?php echo esc_attr($type_class) . esc_attr($fw_class); ?>">
                    <?php if ($is_video) : ?>
                        <?php if ($loop_video) : ?>
                            <video
                                class="image-item"
                                autoplay
                                loop
                                muted
                                playsinline
                                preload="metadata">
                                <source
                                    src="<?php echo esc_url($asset_url); ?>"
                                    type="<?php echo esc_attr($asset_mime); ?>">
                            </video>
                        <?php else : ?>
                            <video
                                class="image-item"
                                playsinline
                                controls
                                controlslist="nodownload nofullscreen noremoteplayback"
                                preload="metadata">
                                <source
                                    src="<?php echo esc_url($asset_url); ?>"
                                    type="<?php echo esc_attr($asset_mime); ?>">
                            </video>
                        <?php endif; ?>
                    <?php else : ?>
                        <img
                            src="<?php echo esc_url($asset_url); ?>"
                            alt="<?php echo esc_attr($titulo); ?> asset"
                            class="image-item" />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
