<?php
/**
 * Hero Component Fields
 * 
 * Define los campos de Carbon Fields para el componente Hero
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'hero_title', __('Título', 'binomio'))
        ->set_width(50),
    
    Field::make('text', 'hero_subtitle', __('Subtítulo', 'binomio'))
        ->set_width(50),
    
    Field::make('textarea', 'hero_description', __('Descripción', 'binomio'))
        ->set_rows(4),
    
    Field::make('image', 'hero_image', __('Imagen de fondo', 'binomio'))
        ->set_value_type('url')
        ->set_width(50),
    
    Field::make('select', 'hero_alignment', __('Alineación', 'binomio'))
        ->set_options(array(
            'left' => __('Izquierda', 'binomio'),
            'center' => __('Centro', 'binomio'),
            'right' => __('Derecha', 'binomio'),
        ))
        ->set_default_value('center')
        ->set_width(50),
    
    Field::make('text', 'hero_button_text', __('Texto del botón', 'binomio'))
        ->set_width(50),
    
    Field::make('text', 'hero_button_link', __('Enlace del botón', 'binomio'))
        ->set_width(50),
);
