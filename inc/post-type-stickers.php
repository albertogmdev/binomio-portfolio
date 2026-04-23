<?php
/**
 * Registro de Custom Post Type: Stickers
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field;

add_action('init', 'binomio_register_post_type_stickers');
function binomio_register_post_type_stickers() {
    $labels = array(
        'name' => __('Stickers', 'binomio'),
        'singular_name' => __('Sticker', 'binomio'),
        'menu_name' => __('Stickers', 'binomio'),
        'name_admin_bar' => __('Sticker', 'binomio'),
        'add_new' => __('Añadir nuevo', 'binomio'),
        'add_new_item' => __('Añadir nuevo sticker', 'binomio'),
        'new_item' => __('Nuevo sticker', 'binomio'),
        'edit_item' => __('Editar sticker', 'binomio'),
        'view_item' => __('Ver sticker', 'binomio'),
        'all_items' => __('Todos los stickers', 'binomio'),
        'search_items' => __('Buscar stickers', 'binomio'),
        'not_found' => __('No se encontraron stickers', 'binomio'),
        'not_found_in_trash' => __('No se encontraron stickers en la papelera', 'binomio'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-format-image',
        'rewrite' => false,
        'supports' => array('title'),
    );

    register_post_type('stickers', $args);
}

add_action('init', 'binomio_attach_division_taxonomy_to_stickers', 25);
function binomio_attach_division_taxonomy_to_stickers() {
    register_taxonomy_for_object_type('division', 'stickers');

    if (!term_exists('studio', 'division')) {
        wp_insert_term('Studio', 'division', array('slug' => 'studio'));
    }

    if (!term_exists('artist', 'division')) {
        wp_insert_term('Artist', 'division', array('slug' => 'artist'));
    }
}

add_action('carbon_fields_register_fields', 'binomio_register_stickers_fields');
function binomio_register_stickers_fields() {
    Container::make('post_meta', __('Datos del Sticker', 'binomio'))
        ->where('post_type', '=', 'stickers')
        ->add_fields(array(
            Field::make('checkbox', 'sticker_show_in_home', __('Mostrar en home', 'binomio')),
            Field::make('image', 'sticker_image', __('Imagen del sticker', 'binomio')),
            Field::make('text', 'sticker_size_desktop', __('Tamaño desktop (px)', 'binomio')),
            Field::make('text', 'sticker_size_mobile', __('Tamaño mobile (px)', 'binomio')),
            Field::make('text', 'sticker_initial_x', __('Posición X inicial desktop (%)', 'binomio')),
            Field::make('text', 'sticker_initial_y', __('Posición Y inicial desktop (%)', 'binomio')),
            Field::make('text', 'sticker_initial_x_mobile', __('Posición X inicial mobile (%)', 'binomio')),
            Field::make('text', 'sticker_initial_y_mobile', __('Posición Y inicial mobile (%)', 'binomio')),
            Field::make('text', 'sticker_rotation', __('Rotación inicial (deg)', 'binomio')),
            Field::make('text', 'sticker_z_index', __('Capa inicial (z-index)', 'binomio')),
        ));
}

add_filter('use_block_editor_for_post_type', 'binomio_disable_gutenberg_for_stickers', 10, 2);
function binomio_disable_gutenberg_for_stickers($use_block_editor, $post_type) {
    if ($post_type === 'stickers') {
        return false;
    }

    return $use_block_editor;
}

add_action('admin_init', 'binomio_remove_editor_support_for_stickers');
function binomio_remove_editor_support_for_stickers() {
    remove_post_type_support('stickers', 'editor');
}
