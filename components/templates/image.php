<?php

/**
 * Section
 */

$image = isset($component['image']) ? $component['image'] : null;
$full = isset($component['full_width']) ? $component['full_width'] : null;
?>

<section class="section-image">
    <?php if (!$full) : ?>
        <div class="container">
        <?php endif; ?>
        <?php if ($image) : ?>
            <img
                src="<?= esc_url(wp_get_attachment_url($image)) ?>"
                alt="image">
        <?php endif; ?>
        <?php if (!$full) : ?>
        </div>
    <?php endif; ?>
</section>