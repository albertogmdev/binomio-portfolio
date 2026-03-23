<?php
/**
 * Hero Component Fields
 * 
 * Define los campos de Carbon Fields para el componente Hero
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('text', 'hero_title', __('Título', 'binomio'))
        ->set_width(50),

    Field::make('text', 'hero_subtitle', __('Subtítulo', 'binomio'))
        ->set_width(50),

    Field::make('rich_text', 'hero_text', __('Texto', 'binomio')),

    Field::make('complex', 'hero_links', __('Links', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(Field::resolve(array(
            Field::make('select', 'tipo', __('Tipo de link', 'binomio'))
                ->set_options(array(
                    'web' => __('Web', 'binomio'),
                    'brandbook' => __('Brandbook', 'binomio'),
                    'other' => __('Otro', 'binomio'),
                ))
                ->set_default_value('web'),

            Field::make('text', 'texto', __('Texto del link', 'binomio')),

            Field::make('text', 'url', __('URL', 'binomio')),
        ))),
));
