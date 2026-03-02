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
require_once get_stylesheet_directory() . '/inc/post-type-proyectos.php';
require_once get_stylesheet_directory() . '/inc/post-type-cases.php';

// Ocultar el editor de contenido en páginas (solo usar constructor de componentes)
add_action('admin_init', 'binomio_hide_editor_on_pages');
function binomio_hide_editor_on_pages() {
    // Ocultar el editor en todas las páginas
    remove_post_type_support('page', 'editor');
}

// Permitir subida de archivos SVG
add_filter('upload_mimes', 'binomio_allow_svg_upload');
function binomio_allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

// Validar archivos SVG al subir
add_filter('wp_check_filetype_and_ext', 'binomio_check_svg_filetype', 10, 4);
function binomio_check_svg_filetype($data, $file, $filename, $mimes) {
    if (strlen($filename) > 4 && strtolower(substr($filename, -4)) === '.svg') {
        $data['type'] = 'image/svg+xml';
        $data['ext'] = 'svg';
    }
    return $data;
}

// Cargar estilos
add_action('wp_enqueue_scripts', 'binomio_enqueue_components_styles');
function binomio_enqueue_components_styles() {
    // Cargar jQuery (incluido en WordPress)
    wp_enqueue_script('jquery');

    // Cargar main.js
    wp_enqueue_script('binomio-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
}

if (!function_exists('is_studio')) {
    function is_studio() {
        if (is_front_page() || is_home()) {
            return false;
        }

        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $path = trim((string) parse_url($request_uri, PHP_URL_PATH), '/');

        return preg_match('#(^|/)studio(/|$)#', $path) === 1;
    }
}

if (!function_exists('is_artist')) {
    function is_artist() {
        if (is_front_page() || is_home()) {
            return false;
        }

        return !is_studio();
    }
}