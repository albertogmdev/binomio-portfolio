<?php
/**
 * Hero Component Template
 * 
 * @var array $component Datos del componente
 */

$title = $component['hero_title'] ?? '';
$subtitle = $component['hero_subtitle'] ?? '';
$description = $component['hero_description'] ?? '';
$image = $component['hero_image'] ?? '';
$alignment = $component['hero_alignment'] ?? 'center';
$button_text = $component['hero_button_text'] ?? '';
$button_link = $component['hero_button_link'] ?? '';
?>

<section class="component-hero component-hero--align-<?php echo esc_attr($alignment); ?>" 
         <?php if ($image): ?>style="background-image: url('<?php echo esc_url($image); ?>');"<?php endif; ?>>
    
    <div class="component-hero__overlay">
        <div class="component-hero__container">
            
            <?php if ($subtitle): ?>
                <p class="component-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($title): ?>
                <h1 class="component-hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            
            <?php if ($description): ?>
                <div class="component-hero__description">
                    <?php echo wpautop(esc_html($description)); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($button_text && $button_link): ?>
                <div class="component-hero__actions">
                    <a href="<?php echo esc_url($button_link); ?>" class="component-hero__button">
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    
</section>
