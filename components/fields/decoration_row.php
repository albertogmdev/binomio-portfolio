<?php
/**
 * Decoration Row Component Fields
 */

use Carbon_Fields\Field as CRB_Field;

return array(
    CRB_Field::make( 'select', 'decoration_row_type', __( 'Fila de decoración', 'binomio' ) )
        ->set_options( array(
            'top'    => __( 'Top (topleft / topright)', 'binomio' ),
            'mid'    => __( 'Mid (midleft / midright)', 'binomio' ),
            'bottom' => __( 'Bottom (bottomleft / bottomright)', 'binomio' ),
        ) ),
);
