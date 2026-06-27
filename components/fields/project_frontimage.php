<?php
/**
 * Project Frontimage Component Fields
 *
 * Portada del proyecto: 1 o 2 assets (single / double). Cada asset puede ser
 * imagen, gif o vídeo (loop muted). Opciones de remove column space y fullwidth.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('checkbox', 'project_frontimage_remove_column_space', __('Remove column space', 'binomio')),

    Field::make('checkbox', 'project_frontimage_fullwidth', __('Fullwidth', 'binomio')),

    Field::make('text', 'project_frontimage_max_width', __('Ancho máximo (px)', 'binomio'))
        ->set_attribute('type', 'number')
        ->set_attribute('min', '0')
        ->set_attribute('placeholder', 'ej: 800'),

    Field::make('media_gallery', 'project_frontimage_images', __('Assets desktop (máx. 2)', 'binomio'))
        ->set_max(2),

    Field::make('media_gallery', 'project_frontimage_images_mobile', __('Assets mobile (máx. 2) — opcional', 'binomio'))
        ->set_max(2),
));
