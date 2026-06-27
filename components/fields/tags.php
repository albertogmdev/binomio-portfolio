<?php
/**
 * Tags Component Fields
 *
 * Bloque de tags centrado. Sin padding por defecto; el padding vertical (px)
 * es configurable.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('set', 'tags_items', __('Tags', 'binomio'))
        ->set_options(array(
            'branding'    => __('Branding', 'binomio'),
            'ux_ui'       => __('UX/UI', 'binomio'),
            'development' => __('Development', 'binomio'),
            'website'     => __('Website', 'binomio'),
        )),

    Field::make('text', 'tags_padding', __('Padding (px)', 'binomio'))
        ->set_attribute('type', 'number')
        ->set_attribute('min', '0')
        ->set_attribute('placeholder', '0'),
));
