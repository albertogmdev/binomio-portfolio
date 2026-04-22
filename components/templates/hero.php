<?php
/**
 * Hero Component Template
 */

$title = tcf_component($component, 'hero_title');
$subtitle = tcf_component($component, 'hero_subtitle');
$text = tcf_component($component, 'hero_text');
$links = isset($component['hero_links']) && is_array($component['hero_links']) ? $component['hero_links'] : array();
$normalized_links = array();

foreach ($links as $link) {
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

    $normalized_links[] = array(
        'text' => $link_text,
        'url' => $link_url,
    );
}
?>

<section class="section-hero">
    <div class="container">
        <div class="decoration-row">
            <span class="decoration decoration--topleft"></span>
            <span class="decoration decoration--topright"></span>
        </div>
        <div class="content-row">
            <div class="content-item content-topleft">
                <?php if ($title !== '') : ?>
                    <h1 class="text-h1"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
                <?php if ($subtitle !== '') : ?>
                    <h2 class="text-h3"><?php echo esc_html($subtitle); ?></h2>
                <?php endif; ?>
            </div>
            <div class="content-item content-topright">
                <?php if ($text !== '') : ?>
                    <div class="body-small"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>
                <?php if (!empty($normalized_links)) : ?>
                    <div class="link-container">
                        <?php foreach ($normalized_links as $link) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="button button--secondary button-icon">
                                <span class="text"><?php echo esc_html($link['text']); ?></span>
                                <span class="icon icon-link"></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="decoration-row">
            <span class="decoration decoration--bottomleft"></span>
            <span class="decoration decoration--bottomright"></span>
        </div>
    </div>
</section>
