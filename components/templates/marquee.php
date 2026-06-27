<?php
/**
 * Marquee Component Template
 *
 * Marquee infinita: los assets se repiten hasta llenar el espacio y el track se
 * duplica para un bucle continuo. Cada asset ocupa el 25% del ancho del container.
 */

$asset_ids = isset($component['marquee_assets']) && is_array($component['marquee_assets']) ? array_filter($component['marquee_assets']) : array();
$fullwidth = !empty($component['marquee_fullwidth']);

// Resolvemos los assets válidos una sola vez.
$assets = array();
foreach ($asset_ids as $asset_id) {
    $asset_id = (int) $asset_id;
    if (empty($asset_id)) {
        continue;
    }
    $url = wp_get_attachment_url($asset_id);
    if (empty($url)) {
        continue;
    }
    $mime = get_post_mime_type($asset_id);
    $assets[] = array(
        'url'      => $url,
        'mime'     => $mime,
        'is_video' => (!empty($mime) && strpos($mime, 'video/') === 0),
    );
}

if (empty($assets)) {
    return;
}

// Repetimos los assets hasta tener un "half" lo bastante ancho para llenar la
// pantalla; luego ese half se duplica en el track para el bucle continuo.
$n           = count($assets);
$min_items   = 12;
$base_repeat = max(2, (int) ceil($min_items / $n));

$base = array();
for ($r = 0; $r < $base_repeat; $r++) {
    foreach ($assets as $asset) {
        $base[] = $asset;
    }
}

$half_count = count($base);
$duration   = max(20, $half_count * 3);
$modifier   = $fullwidth ? 'marquee--fullwidth' : 'marquee--contained';
$titulo     = get_the_title();
?>

<section class="section-marquee">
    <div class="marquee <?php echo esc_attr($modifier); ?>" style="--marquee-duration: <?php echo (int) $duration; ?>s;">
        <div class="marquee-viewport">
            <div class="marquee-track">
                <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                    <?php foreach ($base as $asset) : ?>
                        <div class="marquee-item"<?php echo $copy > 0 ? ' aria-hidden="true"' : ''; ?>>
                            <?php if ($asset['is_video']) : ?>
                                <video autoplay loop muted playsinline preload="metadata">
                                    <source src="<?php echo esc_url($asset['url']); ?>" type="<?php echo esc_attr($asset['mime']); ?>">
                                </video>
                            <?php else : ?>
                                <img src="<?php echo esc_url($asset['url']); ?>" alt="<?php echo esc_attr($titulo); ?>" />
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
