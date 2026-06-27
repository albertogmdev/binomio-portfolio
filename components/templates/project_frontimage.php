<?php
/**
 * Project Frontimage Component Template
 *
 * Dos galerías (desktop / mobile). En mobile, si no hay assets propios se usan
 * los de desktop, y se ignoran el aspect ratio y el ancho máximo.
 */

$desktop = isset($component['project_frontimage_images']) && is_array($component['project_frontimage_images'])
    ? array_values(array_filter($component['project_frontimage_images']))
    : array();

$mobile = isset($component['project_frontimage_images_mobile']) && is_array($component['project_frontimage_images_mobile'])
    ? array_values(array_filter($component['project_frontimage_images_mobile']))
    : array();

if (empty($desktop)) {
    return;
}

// Fallback: si no hay galería mobile, se usan los assets de desktop.
if (empty($mobile)) {
    $mobile = $desktop;
}

$titulo     = get_the_title();
$remove_gap = !empty($component['project_frontimage_remove_column_space']);
$fullwidth  = !empty($component['project_frontimage_fullwidth']);
$max_width  = isset($component['project_frontimage_max_width']) ? trim((string) $component['project_frontimage_max_width']) : '';

$desktop_variant = count($desktop) === 2 ? 'double' : 'single';
$mobile_variant  = count($mobile) === 2 ? 'double' : 'single';

$classes = array('section-frontimage');
if ($remove_gap) {
    $classes[] = 'section-frontimage--nogap';
}
if ($fullwidth) {
    $classes[] = 'section-frontimage--fullwidth';
}

$container_style = '';
if ($max_width !== '' && (int) $max_width > 0) {
    $container_style = ' style="max-width:' . (int) $max_width . 'px;margin-left:auto;margin-right:auto;"';
}

// Render de los assets de una galería (imágenes / gifs / vídeos en loop muted).
$render_items = function ($asset_ids, $titulo) {
    foreach ($asset_ids as $image_id) {
        $image_id = (int) $image_id;
        if (empty($image_id)) {
            continue;
        }
        $url = wp_get_attachment_url($image_id);
        if (empty($url)) {
            continue;
        }
        $mime     = get_post_mime_type($image_id);
        $is_video = !empty($mime) && strpos($mime, 'video/') === 0;

        if ($is_video) {
            echo '<video class="frontimage" autoplay loop muted playsinline preload="metadata">';
            echo '<source src="' . esc_url($url) . '" type="' . esc_attr($mime) . '">';
            echo '</video>';
        } else {
            echo '<img src="' . esc_url($url) . '" alt="' . esc_attr($titulo) . ' portada" class="frontimage" />';
        }
    }
};
?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="container"<?php echo $container_style; ?>>
        <div class="frontimage-view frontimage-view--desktop frontimage--<?php echo esc_attr($desktop_variant); ?>">
            <div class="frontimage-grid">
                <?php $render_items($desktop, $titulo); ?>
            </div>
        </div>
        <div class="frontimage-view frontimage-view--mobile frontimage--<?php echo esc_attr($mobile_variant); ?>">
            <div class="frontimage-grid">
                <?php $render_items($mobile, $titulo); ?>
            </div>
        </div>
    </div>
</section>
