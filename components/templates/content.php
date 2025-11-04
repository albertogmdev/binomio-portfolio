<?php
/**
 * Content Component Template
 * 
 * @var array $component Datos del componente
 */

$title = $component['content_title'] ?? '';
$text = $component['content_text'] ?? '';
$width = $component['content_width'] ?? 'medium';
$bg = $component['content_bg'] ?? 'white';
?>

<section class="component-content component-content--<?php echo esc_attr($width); ?> component-content--bg-<?php echo esc_attr($bg); ?>">
    <div class="component-content__container">
        
        <?php if ($title): ?>
            <h2 class="component-content__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <?php if ($text): ?>
            <div class="component-content__text">
                <?php echo wp_kses_post($text); ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>
