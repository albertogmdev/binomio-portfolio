<?php
/**
 * Fullgallery Component Template
 */

$items = isset($component['fullgallery_items']) && is_array($component['fullgallery_items'])
    ? $component['fullgallery_items']
    : array();

if (empty($items)) {
    return;
}

$titulo = get_the_title();
?>

<section class="section-fullgallery">
    <div class="gallery-grid">
        <?php foreach ($items as $item) : ?>
            <?php
            $asset_id   = isset($item['asset_id']) ? (int) $item['asset_id'] : 0;
            $loop_video = !empty($item['asset_loop_video']);
            if (empty($asset_id)) {
                continue;
            }
            $asset_mime = get_post_mime_type($asset_id);
            $asset_url  = wp_get_attachment_url($asset_id);
            if (empty($asset_url)) {
                continue;
            }
            $is_video = !empty($asset_mime) && strpos($asset_mime, 'video/') === 0;
            ?>
            <?php if ($is_video) : ?>
                <?php if ($loop_video) : ?>
                    <video
                        class="gallery-item"
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
                        class="gallery-item"
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
                    class="gallery-item" />
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
