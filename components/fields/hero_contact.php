<?php
/**
 * Hero Component Fields
 * 
 * Define los campos de Carbon Fields para el componente Hero Contact
 */

use TranslatableCarbonFields\Fields\Field;
use Carbon_Fields\Field as CRB_Field;

return Field::resolve(array(
    Field::make('text', 'hero_title', __('Título', 'binomio'))
        ->set_width(50),

    Field::make('text', 'hero_subtitle', __('Subtítulo', 'binomio'))
        ->set_width(50),

    Field::make('rich_text', 'hero_text', __('Texto', 'binomio')),
));
