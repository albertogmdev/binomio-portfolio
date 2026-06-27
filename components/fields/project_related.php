<?php
/**
 * Project Related Component Fields
 *
 * Proyectos relacionados (máx. 3).
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('association', 'project_related_items', __('Proyectos relacionados (máx. 3)', 'binomio'))
        ->set_types(array(
            array(
                'type'      => 'post',
                'post_type' => 'projects',
            ),
        ))
        ->set_max(3),
));
