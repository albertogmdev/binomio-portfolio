<?php
/**
 * Main Page Component Fields
 *
 * Dropdown para elegir qué hero (studio o artist) renderizar en la página principal.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('select', 'main_page_section', __('Sección', 'binomio'))
        ->set_options(array(
            'studio' => __('Studio', 'binomio'),
            'artist' => __('Artist', 'binomio'),
        ))
        ->set_default_value('studio'),
));
