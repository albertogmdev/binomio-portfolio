<?php
/**
 * Hero Component Fields
 * 
 * Define los campos de Carbon Fields para el componente Hero Contact
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'hero_title', __('Título', 'binomio'))
        ->set_width(50),

    Field::make('text', 'hero_subtitle', __('Subtítulo', 'binomio'))
        ->set_width(50),

    Field::make('rich_text', 'hero_text', __('Texto', 'binomio')),
);
