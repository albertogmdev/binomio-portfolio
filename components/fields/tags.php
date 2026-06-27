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
        ->set_options(binomio_get_tag_options()),

    Field::make('text', 'tags_padding', __('Padding (px)', 'binomio'))
        ->set_attribute('type', 'number')
        ->set_attribute('min', '0')
        ->set_attribute('placeholder', '0'),
));
