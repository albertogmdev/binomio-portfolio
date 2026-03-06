<?php

/**
 * About Info Component Template
 */

$image = isset($component['about_info_image']) ? $component['about_info_image'] : null;
$title = isset($component['about_info_title']) ? $component['about_info_title'] : null;
$content = isset($component['about_info_content']) ? $component['about_info_content'] : null;
$brand_image = isset($component['about_info_brand_image']) ? $component['about_info_brand_image'] : null;
$links = isset($component['about_info_links']) ? $component['about_info_links'] : null;
$show_press = !empty($component['about_info_show_press']);
$show_downloads = !empty($component['about_info_show_downloads']);
?>

<section class="section-about_info">
    <div class="container">
        <div class="main-content">
            <div class="image-col">
                <?php if ($image) : ?>
                    <img
                        src="<?= esc_url(wp_get_attachment_url($image)) ?>"
                        alt="About Image">
                <?php endif; ?>
            </div>
            <div class="content-col">
                <?php if ($title) : ?>
                    <h3 class="content-title body-large">
                        <?= esc_html($title) ?>
                    </h3>
                <?php endif; ?>
                <?php if ($content) : ?>
                    <div class="content-text body-small ">
                        <?= $content ?>
                    </div>
                <?php endif; ?>
                <div class="content-footer">
                    <div class="footer-brand">
                        <?php if ($brand_image) : ?>
                            <img src="<?= esc_url(wp_get_attachment_url($brand_image)) ?>" alt="Brand Image">
                        <?php endif; ?>
                    </div>
                    <ul class="footer-links">
                        <?php if ($links) : ?>
                            <?php foreach ($links as $link) : ?>
                                <li>
                                    <a
                                        class="button" 
                                        href="<?= esc_url($link['url']) ?>"
                                    >
                                        [ <?= esc_html($link['texto']) ?> ]
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>