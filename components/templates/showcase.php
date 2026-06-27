<?php
/**
 * Showcase Component Template
 */

$icon_id     = isset($component['showcase_icon']) ? (int) $component['showcase_icon'] : 0;
$title       = tcf_component($component, 'showcase_title');
$description = tcf_component($component, 'showcase_description');
$asset_id    = isset($component['showcase_asset']) ? (int) $component['showcase_asset'] : 0;
$asset_loop  = !empty($component['showcase_asset_loop']);
$max_width   = isset($component['showcase_asset_max_width']) ? trim((string) $component['showcase_asset_max_width']) : '';
$aspect_raw  = isset($component['showcase_asset_aspect']) ? trim((string) $component['showcase_asset_aspect']) : '';

if (empty($icon_id) && $title === '' && $description === '' && empty($asset_id)) {
    return;
}

$titulo = get_the_title();

// Datos del asset.
$asset_mime = !empty($asset_id) ? get_post_mime_type($asset_id) : '';
$asset_url  = !empty($asset_id) ? wp_get_attachment_url($asset_id) : '';
$is_video   = !empty($asset_mime) && strpos($asset_mime, 'video/') === 0;
$has_aspect = ($aspect_raw !== '' && preg_match('#^[0-9]+(?:\.[0-9]+)?\s*/\s*[0-9]+(?:\.[0-9]+)?$#', $aspect_raw));

// Estilos inline del asset (max-width y aspect-ratio).
$asset_style_parts = array();
if ($max_width !== '' && (int) $max_width > 0) {
    $asset_style_parts[] = 'max-width:' . (int) $max_width . 'px';
}
if ($has_aspect) {
    $asset_style_parts[] = 'aspect-ratio:' . str_replace(' ', '', $aspect_raw);
}
$asset_style = !empty($asset_style_parts) ? ' style="' . esc_attr(implode(';', $asset_style_parts)) . '"' : '';

// Clases del asset. Los vídeos no aplican el aspect ratio 16/9 por defecto.
$asset_classes = array('showcase-asset');
if ($is_video) {
    $asset_classes[] = 'showcase-asset--video';
}
if ($has_aspect) {
    $asset_classes[] = 'showcase-asset--has-aspect';
}
$asset_class = implode(' ', $asset_classes);
?>

<section class="section-showcase">
    <div class="container">
        <?php if (!empty($icon_id) || $title !== '' || $description !== '') : ?>
            <div class="showcase-header">
                <?php if (!empty($icon_id)) : ?>
                    <?php $icon_url = wp_get_attachment_url($icon_id); ?>
                    <?php if (!empty($icon_url)) : ?>
                        <img
                            src="<?php echo esc_url($icon_url); ?>"
                            alt="<?php echo esc_attr($title !== '' ? $title : $titulo); ?>"
                            class="showcase-icon" />
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($title !== '') : ?>
                    <h2 class="showcase-title text-h1"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if ($description !== '') : ?>
                    <div class="showcase-description body-small"><?php echo wp_kses_post($description); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($asset_id)) : ?>
            <?php if (!empty($asset_url)) : ?>
                <div class="<?php echo esc_attr($asset_class); ?>"<?php echo $asset_style; ?>>
                    <?php if ($is_video) : ?>
                        <?php if ($asset_loop) : ?>
                            <video autoplay loop muted playsinline preload="metadata">
                                <source src="<?php echo esc_url($asset_url); ?>" type="<?php echo esc_attr($asset_mime); ?>">
                            </video>
                        <?php else : ?>
                            <video
                                playsinline
                                controls
                                controlslist="nodownload nofullscreen noremoteplayback"
                                preload="metadata">
                                <source src="<?php echo esc_url($asset_url); ?>" type="<?php echo esc_attr($asset_mime); ?>">
                            </video>
                        <?php endif; ?>
                    <?php else : ?>
                        <img
                            src="<?php echo esc_url($asset_url); ?>"
                            alt="<?php echo esc_attr($titulo); ?> asset" />
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
