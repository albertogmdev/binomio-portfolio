<?php
/**
 * About Info Component Fields
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('image', 'about_info_image', __('Imagen', 'binomio')),

    Field::make('text', 'about_info_title', __('Titulo', 'binomio')),

    Field::make('rich_text', 'about_info_content', __('Content', 'binomio')),

    Field::make('image', 'about_info_brand_image', __('Brand Image', 'binomio'))
        ->set_width(50),

    Field::make('complex', 'about_info_links', __('Links', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(Field::resolve(array(
            Field::make('text', 'texto', __('Texto del link', 'binomio')),
            Field::make('text', 'url', __('URL', 'binomio')),
        ))),

    Field::make('checkbox', 'about_info_show_press', __('Show Press', 'binomio'))
        ->set_option_value('yes')
        ->set_width(50),

    Field::make('checkbox', 'about_info_show_downloads', __('Show Downloads', 'binomio'))
        ->set_option_value('yes')
        ->set_width(50),
));
