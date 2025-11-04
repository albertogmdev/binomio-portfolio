<?php
/**
 * Content Component Fields
 * 
 * Componente simple de contenido con título y texto
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'content_title', __('Título', 'binomio'))
        ->set_width(100),
    
    Field::make('rich_text', 'content_text', __('Contenido', 'binomio'))
        ->set_width(100)
        ->set_help_text('Contenido principal del bloque'),
    
    Field::make('select', 'content_width', __('Ancho del contenedor', 'binomio'))
        ->add_options(array(
            'narrow' => __('Estrecho', 'binomio'),
            'medium' => __('Medio', 'binomio'),
            'wide' => __('Ancho', 'binomio'),
        ))
        ->set_default_value('medium')
        ->set_width(50),
    
    Field::make('select', 'content_bg', __('Color de fondo', 'binomio'))
        ->add_options(array(
            'white' => __('Blanco', 'binomio'),
            'gray' => __('Gris', 'binomio'),
            'dark' => __('Oscuro', 'binomio'),
        ))
        ->set_default_value('white')
        ->set_width(50),
);
