<?php
/**
 * Marquee Spaced Component Template
 *
 * Marquee infinita con 80px de espacio entre assets y tamaños alternos
 * (grande 40% / pequeña 20%). Los assets se centran verticalmente.
 */

$asset_ids = isset($component['marquee_spaced_assets']) && is_array($component['marquee_spaced_assets']) ? array_filter($component['marquee_spaced_assets']) : array();
$fullwidth = !empty($component['marquee_spaced_fullwidth']);

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

// Repetimos los assets hasta tener un "half" lo bastante ancho; el half debe
// tener un número par de items para que la alternancia grande/pequeña no se
// rompa al duplicar el track.
$n           = count($assets);
$min_items   = 12;
$base_repeat = max(2, (int) ceil($min_items / $n));

$base = array();
for ($r = 0; $r < $base_repeat; $r++) {
    foreach ($assets as $asset) {
        $base[] = $asset;
    }
}
if (count($base) % 2 !== 0) {
    $base[] = $assets[0];
}

$half_count = count($base);
$duration   = max(20, $half_count * 4);
$modifier   = $fullwidth ? 'marquee--fullwidth' : 'marquee--contained';
$titulo     = get_the_title();
?>

<section class="section-marquee-spaced">
    <div class="marquee marquee--spaced <?php echo esc_attr($modifier); ?>" style="--marquee-duration: <?php echo (int) $duration; ?>s;">
        <div class="marquee-viewport">
            <div class="marquee-track">
                <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                    <?php foreach ($base as $index => $asset) : ?>
                        <?php $size_class = ($index % 2 === 0) ? 'marquee-item--large' : 'marquee-item--small'; ?>
                        <div class="marquee-item <?php echo esc_attr($size_class); ?>"<?php echo $copy > 0 ? ' aria-hidden="true"' : ''; ?>>
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
