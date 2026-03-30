<?php
/**
 * Section Container Component Template
 *
 * Sección con decoraciones en esquinas y columnas configurables (flex/grid).
 */

$style       = $component['section_container_style']        ?? 'default';
$cols_d      = $component['section_container_cols_desktop'] ?? 'auto';
$cols_m      = $component['section_container_cols_mobile']  ?? 'auto';
$direction   = $component['section_container_direction']    ?? 'row';
$align       = $component['section_container_align']        ?? 'stretch';
$justify     = $component['section_container_justify']      ?? 'start';
$columns     = isset( $component['section_container_columns'] ) && is_array( $component['section_container_columns'] )
    ? $component['section_container_columns']
    : array();

$section_class  = 'section-hero';
$section_class .= ( $style !== 'default' ) ? ' section-hero--' . esc_attr( $style ) : '';

$columns_class  = 'content-columns';
if ( $direction !== 'row' ) $columns_class .= ' content-columns--' . esc_attr( $direction );

// CSS custom properties para el grid.
$inline_style = '';
if ( $cols_d   !== 'auto'    ) $inline_style .= '--cols-d:'   . (int) $cols_d . ';';
if ( $cols_m   !== 'auto'    ) $inline_style .= '--cols-m:'   . (int) $cols_m . ';';
if ( $align    !== 'stretch' ) $inline_style .= '--col-align:' . esc_attr( $align ) . ';';
if ( $justify  !== 'start'   ) $inline_style .= '--col-justify:' . esc_attr( $justify ) . ';';
$columns_style = $inline_style ? ' style="' . esc_attr( $inline_style ) . '"' : '';
?>

<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">

        <?php if ( ! empty( $columns ) ) : ?>
            <div class="<?php echo esc_attr( $columns_class ); ?>"<?php echo $columns_style; ?>>

                <?php foreach ( $columns as $col ) :
                    $col_type     = $col['_type']         ?? '';
                    $span_d       = $col['col_span_d']    ?? 'auto';
                    $span_m       = $col['col_span_m']    ?? 'auto';
                    $align_self   = $col['col_align_self']   ?? 'auto';
                    $justify_self = $col['col_justify_self'] ?? 'stretch';
                    if ( ! $col_type ) continue;

                    $col_class = 'content-col content-col--' . esc_attr( $col_type );
                    if ( $span_d !== 'auto' ) $col_class .= ' col-span-d-' . esc_attr( $span_d );
                    if ( $span_m !== 'auto' ) $col_class .= ' col-span-m-' . esc_attr( $span_m );

                    $col_inline = array();
                    if ( $align_self   !== 'auto'    ) $col_inline[] = 'align-self:'   . esc_attr( $align_self );
                    if ( $justify_self !== 'stretch' ) $col_inline[] = 'justify-self:' . esc_attr( $justify_self );
                    $col_style = $col_inline ? ' style="' . implode( ';', $col_inline ) . '"' : '';
                ?>
                    <div class="<?php echo esc_attr( $col_class ); ?>"<?php echo $col_style; ?>>
                        <?php if ( $col_type !== 'spacer' ) Binomio_Component_Loader::render_component( $col_type, $col ); ?>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</section>
