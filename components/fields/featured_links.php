<?php
/**
 * Featured Links Component Fields
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'featured_links_title', __('Titulo', 'binomio'))
        ->set_width(50),

    Field::make('text', 'featured_links_subtitle', __('Subtitulo', 'binomio'))
        ->set_width(50),

    Field::make('complex', 'featured_links_items', __('Items', 'binomio'))
        ->set_layout('tabbed-horizontal')
        ->add_fields(array(
            Field::make('text', 'name', __('Nombre', 'binomio'))
                ->set_width(25),

            Field::make('text', 'text', __('Text', 'binomio'))
                ->set_width(25),

            Field::make('text', 'link', __('Link', 'binomio'))
                ->set_width(25),

            Field::make('text', 'year', __('Ano', 'binomio'))
                ->set_width(25),
        )),
);
