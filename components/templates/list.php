<?php

/**
 * List Component Template
 * 
 * @var array $component Datos del componente
 */

$items = $component['list_items'] ?? [];
?>

<ul class="component-list">
    <?php foreach ($items as $item): ?>
        <li class="component-list__item">
            <?php if (!empty($item['photo'])): ?>
                <div class="component-list__item-photo">
                    <img src="<?php echo esc_url($item['photo']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                </div>
            <?php endif; ?>
            <?php if (!empty($item['title']) && count($item['title']) > 0): ?>
                <?php foreach ($item['title'] as $text): ?>
                    <p class="component-list__item-title"><?php echo esc_html($text['text']); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>