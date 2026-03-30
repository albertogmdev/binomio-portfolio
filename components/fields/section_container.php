<?php
/**
 * Section Container Component Fields
 *
 * Contenedor genérico con decoraciones en esquinas y columnas configurables.
 * Cada columna puede contener cualquier componente disponible (igual que el page builder).
 */

use TranslatableCarbonFields\Fields\Field;
use Carbon_Fields\Field as CRB_Field;

// Carga dinámica de todos los componentes disponibles como opciones de columna.
$components_dir  = get_stylesheet_directory() . '/components/fields';
$component_files = glob( $components_dir . '/*.php' );

// Campos de span que se prependen a CADA columna (incluido spacer).
$col_span_fields = array(
    CRB_Field::make( 'select', 'col_span_d', __( 'Span desktop', 'binomio' ) )
        ->set_options( array(
            'auto' => __( 'Auto', 'binomio' ),
            '1'    => '1',
            '2'    => '2',
            '3'    => '3',
            '4'    => '4',
        ) )
        ->set_width( 34 ),

    CRB_Field::make( 'select', 'col_span_m', __( 'Span mobile', 'binomio' ) )
        ->set_options( array(
            'auto'   => __( 'Auto', 'binomio' ),
            'full'   => __( 'Full', 'binomio' ),
            'hidden' => __( 'Oculto', 'binomio' ),
        ) )
        ->set_width( 33 ),

    CRB_Field::make( 'select', 'col_align_self', __( 'Alinear col', 'binomio' ) )
        ->set_options( array(
            'auto'    => __( 'Auto', 'binomio' ),
            'start'   => __( 'Top', 'binomio' ),
            'center'  => __( 'Center', 'binomio' ),
            'end'     => __( 'Bottom', 'binomio' ),
            'stretch' => __( 'Stretch', 'binomio' ),
        ) )
        ->set_width( 50 ),

    CRB_Field::make( 'select', 'col_justify_self', __( 'Justificar col', 'binomio' ) )
        ->set_options( array(
            'stretch' => __( 'Stretch', 'binomio' ),
            'start'   => __( 'Start', 'binomio' ),
            'center'  => __( 'Center', 'binomio' ),
            'end'     => __( 'End', 'binomio' ),
        ) )
        ->set_width( 50 ),
);

$columns_field = CRB_Field::make( 'complex', 'section_container_columns', __( 'Columnas', 'binomio' ) )
    ->set_layout( 'tabbed-horizontal' );

// Spacer: hueco vacío, solo tiene los controles de span.
$columns_field->add_fields( 'spacer', __( 'Spacer', 'binomio' ), $col_span_fields );

foreach ( $component_files as $file ) {
    $slug = basename( $file, '.php' );
    if ( $slug === 'section_container' ) {
        continue; // evitar recursión infinita
    }
    $sub_fields = include $file;
    if ( is_array( $sub_fields ) ) {
        $label  = ucwords( str_replace( '_', ' ', $slug ) );
        $merged = array_merge( $col_span_fields, $sub_fields );
        $columns_field->add_fields( $slug, $label, $merged );
    }
}

return Field::resolve( array(

    CRB_Field::make( 'select', 'section_container_style', __( 'Estilo de sección', 'binomio' ) )
        ->set_options( array(
            'default' => __( 'Default', 'binomio' ),
            'contact'  => __( 'Contact', 'binomio' ),
            'dark'     => __( 'Dark', 'binomio' ),
            'light'    => __( 'Light', 'binomio' ),
        ) )
        ->set_width( 25 ),

    CRB_Field::make( 'select', 'section_container_cols_desktop', __( 'Cols desktop', 'binomio' ) )
        ->set_options( array(
            'auto' => __( 'Auto', 'binomio' ),
            '2'    => '2',
            '3'    => '3',
            '4'    => '4',
        ) )
        ->set_width( 25 ),

    CRB_Field::make( 'select', 'section_container_cols_mobile', __( 'Cols mobile', 'binomio' ) )
        ->set_options( array(
            'auto' => __( 'Auto', 'binomio' ),
            '1'    => '1',
            '2'    => '2',
        ) )
        ->set_width( 25 ),

    CRB_Field::make( 'select', 'section_container_direction', __( 'Dirección', 'binomio' ) )
        ->set_options( array(
            'row'                => __( 'Normal', 'binomio' ),
            'row-reverse'        => __( 'Reverse', 'binomio' ),
            'row-reverse-mobile' => __( 'Reverse mobile', 'binomio' ),
        ) )
        ->set_width( 25 ),

    CRB_Field::make( 'select', 'section_container_align', __( 'Alineación vertical', 'binomio' ) )
        ->set_options( array(
            'stretch'    => __( 'Stretch', 'binomio' ),
            'start'      => __( 'Top', 'binomio' ),
            'center'     => __( 'Center', 'binomio' ),
            'end'        => __( 'Bottom', 'binomio' ),
        ) )
        ->set_width( 25 ),

    CRB_Field::make( 'select', 'section_container_justify', __( 'Alineación horizontal', 'binomio' ) )
        ->set_options( array(
            'start'         => __( 'Start', 'binomio' ),
            'center'        => __( 'Center', 'binomio' ),
            'end'           => __( 'End', 'binomio' ),
            'space-between' => __( 'Space between', 'binomio' ),
            'space-around'  => __( 'Space around', 'binomio' ),
        ) )
        ->set_width( 25 ),

    $columns_field,

) );
