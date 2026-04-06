<?php

/**
 * Hero Contact Component Template
 */

$title = tcf_component($component, 'hero_title');
$subtitle = tcf_component($component, 'hero_subtitle');
$text = tcf_component($component, 'hero_text');
?>

<section class="section-hero section-hero--contact">
    <div class="container">
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
            </div>
        </div>
    </div>
</section>