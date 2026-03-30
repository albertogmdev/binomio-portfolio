<?php
/**
 * Decoration Row Component Template
 */

$type = $component['decoration_row_type'] ?? 'top';

$map = array(
    'top'    => array( 'topleft',    'topright' ),
    'mid'    => array( 'midleft',    'midright' ),
    'bottom' => array( 'bottomleft', 'bottomright' ),
);

$pair = $map[ $type ] ?? $map['top'];
?>
<div class="container">
    <div class="decoration-row">
        <span class="decoration decoration--<?php echo esc_attr( $pair[0] ); ?>"></span>
        <span class="decoration decoration--<?php echo esc_attr( $pair[1] ); ?>"></span>
    </div>
</div>
