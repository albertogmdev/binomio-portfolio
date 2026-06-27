<?php
/**
 * Credits Component Template
 */

$tags    = isset($component['credits_tags']) && is_array($component['credits_tags']) ? array_filter($component['credits_tags']) : array();
$content = tcf_component($component, 'credits_content');
$links   = isset($component['credits_links']) && is_array($component['credits_links']) ? $component['credits_links'] : array();

$normalized_links = array();
foreach ($links as $link) {
    $link_text = tcf_item($link, 'texto');
    $raw_url   = $link['url'] ?? '';
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
        'url'  => $link_url,
    );
}

if (empty($tags) && $content === '' && empty($normalized_links)) {
    return;
}
?>

<section class="section-credits">
    <div class="container">
        <?php if (!empty($tags)) : ?>
            <div class="tag-container">
                <?php foreach ($tags as $tag) : ?>
                    <div class="tag"><?php echo esc_html($tag); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p class="credits-title body-large">[ <?php echo esc_html(bnm_t('single_credits', 'CREDITS')); ?> ]</p>
        <?php if ($content !== '') : ?>
            <div class="credits-content body-small">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($normalized_links)) : ?>
            <div class="link-container">
                <?php foreach ($normalized_links as $link) : ?>
                    <a
                        href="<?php echo esc_url($link['url']); ?>"
                        class="button button--secondary button-icon"
                        target="_blank"
                        rel="noopener noreferrer">
                        <span class="text"><?php echo esc_html($link['text']); ?></span>
                        <span class="icon icon-link"></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
