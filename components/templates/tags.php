<?php
/**
 * Tags Component Template
 *
 * Bloque de tags centrado. Sin padding por defecto; padding vertical opcional (px).
 */

$tags = isset($component['tags_items']) && is_array($component['tags_items'])
    ? array_filter($component['tags_items'])
    : array();

if (empty($tags)) {
    return;
}

$padding       = isset($component['tags_padding']) ? max(0, (int) $component['tags_padding']) : 0;
$padding_style = $padding > 0 ? ' style="padding-top:' . $padding . 'px;padding-bottom:' . $padding . 'px;"' : '';
?>

<section class="section-tags"<?php echo $padding_style; ?>>
    <div class="container">
        <div class="tag-container">
            <?php foreach ($tags as $tag) :
                $tag_label = binomio_get_tag_label($tag);
                if ($tag_label === '') {
                    continue;
                }
                ?>
                <div class="tag"><?php echo esc_html($tag_label); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
