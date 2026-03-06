<?php
/**
 * Hero Component Fields
 * 
 * Define los campos de Carbon Fields para el componente Hero
 */

use Carbon_Fields\Field;

return array(
    Field::make('checkbox', 'full_width', __('Full width', 'binomio'))
        ->set_option_value('yes'),
        
    Field::make('image', 'image', __('Imagen', 'binomio'))
);
