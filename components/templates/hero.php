<?php
/**
 * Hero Component Template
 */

$title = tcf_component($component, 'hero_title');
$subtitle = tcf_component($component, 'hero_subtitle');
$text = tcf_component($component, 'hero_text');
$text_font = isset($component['hero_text_font']) ? $component['hero_text_font'] : 'regular';
$text_class = 'body-small' . ($text_font === 'bold' ? ' alter-font' : '');
$tags = isset($component['hero_tags']) && is_array($component['hero_tags']) ? array_filter($component['hero_tags']) : array();
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
                    <div class="<?php echo esc_attr($text_class); ?>"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="decoration-row">
            <span class="decoration decoration--midleft"></span>
            <span class="decoration decoration--midright"></span>
        </div>
        <div class="content-row">
            <div class="content-item content-item--empty content-bottomleft"></div>
            <div class="content-item content-bottomright">
                <?php if (!empty($tags)) : ?>
                    <div class="tag-container">
                        <?php foreach ($tags as $tag) : ?>
                            <div class="tag"><?php echo esc_html($tag); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($normalized_links)) : ?>
                    <div class="link-container">
                        <?php foreach ($normalized_links as $link) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="button button--secondary button-icon" target="_blank" rel="noopener noreferrer">
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
