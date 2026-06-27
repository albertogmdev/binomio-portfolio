<?php
/**
 * Title + Text Component Template
 *
 * Componente global: título en mayúsculas + contenido WYSIWYG.
 */

$title   = tcf_component($component, 'title_text_title');
$content = tcf_component($component, 'title_text_content');

if ($title === '' && $content === '') {
    return;
}
?>

<section class="section-title-text">
    <div class="container">
        <?php if ($title !== '') : ?>
            <p class="title-text-title body-large"><?php echo esc_html($title); ?></p>
        <?php endif; ?>
        <?php if ($content !== '') : ?>
            <div class="title-text-content body-small"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>
    </div>
</section>
