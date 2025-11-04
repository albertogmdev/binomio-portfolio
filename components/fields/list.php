<?php

/**
 * List Component Fields
 * 
 * Define los campos de Carbon Fields para el componente List
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'hero_title', __('Título', 'binomio'))
        ->set_width(50),

    Field::make('complex', 'list_items', __('Lista de items', 'binomio'))
        ->add_fields(array(
            Field::make('complex', 'title', __('Título', 'binomio'))
                ->add_fields(array(
                    Field::make('text', 'text', __('Texto', 'binomio')),
                )),
            Field::make('image', 'photo', __('Imagen del item', 'binomio'))
                ->set_value_type('url'),
        ))

);
