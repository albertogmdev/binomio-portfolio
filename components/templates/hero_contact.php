<?php

/**
 * Hero Contact Component Template
 */

$title = isset($component['hero_title']) ? (string) $component['hero_title'] : '';
$subtitle = isset($component['hero_subtitle']) ? (string) $component['hero_subtitle'] : '';
$text = isset($component['hero_text']) ? (string) $component['hero_text'] : '';
?>

<section class="section-hero section-hero--contact">
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
            </div>
        </div>
        <div class="decoration-row">
            <span class="decoration decoration--midleft"></span>
            <span class="decoration decoration--midright"></span>
        </div>
        <div class="content-row">
            <div class="content-item content-bottomleft">
            </div>
            <div class="content-item content-bottomright">
                <form>
                    <input type="text" class="input" placeholder="Name">
                    <input type="text" class="input" placeholder="Company">
                    <input type="email" class="input" placeholder="Email">
                    <textarea class="input" name="idea" placeholder="Tell us your idea"></textarea>
                    <button class="button">Send</button>
                </form>
            </div>
        </div>
        <div class="decoration-row">
            <span class="decoration decoration--bottomleft"></span>
            <span class="decoration decoration--bottomright"></span>
        </div>
    </div>
</section>