<?php
/**
 * Binomio Theme Functions
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Cargar Carbon Fields
add_action('after_setup_theme', 'binomio_load_carbon_fields');
function binomio_load_carbon_fields() {
    \Carbon_Fields\Carbon_Fields::boot();
}

// Cargar el sistema de componentes
require_once get_stylesheet_directory() . '/inc/component-loader.php';

// Ocultar el editor de contenido en páginas (solo usar constructor de componentes)
add_action('admin_init', 'binomio_hide_editor_on_pages');
function binomio_hide_editor_on_pages() {
    // Ocultar el editor en todas las páginas
    remove_post_type_support('page', 'editor');
}

// Cargar estilos y scripts de componentes
add_action('wp_enqueue_scripts', 'binomio_enqueue_components_styles');
function binomio_enqueue_components_styles() {
    // Cargar jQuery (incluido en WordPress)
    wp_enqueue_script('jquery');
    
    // Cargar script principal
    wp_enqueue_script(
        'binomio-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        '1.0',
        true
    );
    
    $components_dir = get_stylesheet_directory() . '/components/templates';
    
    if (is_dir($components_dir)) {
        $css_files = glob($components_dir . '/*.css');
        
        foreach ($css_files as $css_file) {
            $component_name = basename($css_file, '.css');
            wp_enqueue_style(
                'component-' . $component_name,
                get_stylesheet_directory_uri() . '/components/templates/' . basename($css_file),
                array(),
                filemtime($css_file)
            );
        }
    }
}

// Theme Options (ejemplo)
add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
    Container::make( 'theme_options', __( 'Theme Options' ) )
        ->add_fields( array(
            Field::make( 'text', 'crb_text', 'Text Field' ),
        ) );
}