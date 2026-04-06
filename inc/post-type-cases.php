<?php
/**
 * Registro de Custom Post Type: Cases
 */

use TranslatableCarbonFields\Fields\Field;
use Carbon_Fields\Container\Container;

add_action('init', 'binomio_register_post_type_cases');
function binomio_register_post_type_cases() {
    $labels = array(
        'name' => __('Cases', 'binomio'),
        'singular_name' => __('Case', 'binomio'),
        'menu_name' => __('Cases', 'binomio'),
        'name_admin_bar' => __('Case', 'binomio'),
        'add_new' => __('Añadir nuevo', 'binomio'),
        'add_new_item' => __('Añadir nuevo case', 'binomio'),
        'new_item' => __('Nuevo case', 'binomio'),
        'edit_item' => __('Editar case', 'binomio'),
        'view_item' => __('Ver case', 'binomio'),
        'all_items' => __('Todos los cases', 'binomio'),
        'search_items' => __('Buscar cases', 'binomio'),
        'not_found' => __('No se encontraron cases', 'binomio'),
        'not_found_in_trash' => __('No se encontraron cases en la papelera', 'binomio'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-portfolio',
        'menu_position' => 26,
        'rewrite' => false,
        'supports' => array('title'),
    );

    register_post_type('cases', $args);
}

add_action('init', 'binomio_attach_division_taxonomy_to_cases', 25);
function binomio_attach_division_taxonomy_to_cases() {
    register_taxonomy_for_object_type('division', 'cases');

    if (!term_exists('studio', 'division')) {
        wp_insert_term('Studio', 'division', array('slug' => 'studio'));
    }

    if (!term_exists('artist', 'division')) {
        wp_insert_term('Artist', 'division', array('slug' => 'artist'));
    }
}

add_action('init', 'binomio_register_cases_division_rewrites', 25);
function binomio_register_cases_division_rewrites() {
    $artist_cases_paths = array(
        binomio_get_route_slug('cases', 'en'),
        binomio_get_route_slug('cases', 'es'),
    );
    $studio_cases_paths = array(
        binomio_get_route_slug('studio', 'en') . '/' . binomio_get_route_slug('cases', 'en'),
        binomio_get_route_slug('studio', 'es') . '/' . binomio_get_route_slug('cases', 'es'),
    );

    foreach (array_unique($artist_cases_paths) as $path) {
        binomio_add_localized_rewrite_rule($path . '/?$', 'index.php?post_type=cases&division=artist', 'top');
    }

    foreach (array_unique($studio_cases_paths) as $path) {
        binomio_add_localized_rewrite_rule($path . '/?$', 'index.php?post_type=cases&division=studio', 'top');
    }
}

add_action('pre_get_posts', 'binomio_filter_cases_by_division');
function binomio_filter_cases_by_division($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    $post_type = $query->get('post_type');
    $is_cases_query = ($post_type === 'cases') || (is_array($post_type) && in_array('cases', $post_type, true)) || $query->is_post_type_archive('cases');

    if (!$is_cases_query) {
        return;
    }

    $division = $query->get('division');

    if (empty($division)) {
        $request_path = binomio_get_request_path();

        if (preg_match('#^(' . preg_quote(binomio_get_route_slug('studio', 'en') . '/' . binomio_get_route_slug('cases', 'en'), '#') . '|' . preg_quote(binomio_get_route_slug('studio', 'es') . '/' . binomio_get_route_slug('cases', 'es'), '#') . ')(/|$)#', $request_path)) {
            $division = 'studio';
            $query->set('division', $division);
        } elseif (preg_match('#^' . preg_quote(binomio_get_route_slug('cases', 'en'), '#') . '(/|$)#', $request_path)) {
            $division = 'artist';
             $query->set('division', $division);
         }
     }

    if (empty($division)) {
        return;
    }

    $query->set('tax_query', array(
        array(
            'taxonomy' => 'division',
            'field' => 'slug',
            'terms' => $division,
        ),
    ));
}

add_action('template_redirect', 'binomio_disable_single_cases');
function binomio_disable_single_cases() {
    if (!is_singular('cases')) {
        return;
    }

    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();
    include get_query_template('404');
    exit;
}

add_action('init', 'binomio_flush_cases_rewrites_once', 40);
function binomio_flush_cases_rewrites_once() {
    $rewrite_version = 'cases_division_rewrite_v2';

    if (get_option('binomio_cases_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('binomio_cases_rewrite_version', $rewrite_version);
}

add_action('carbon_fields_register_fields', 'binomio_register_cases_fields');
function binomio_register_cases_fields() {
    Container::make('post_meta', __('Datos del Case', 'binomio'))
        ->where('post_type', '=', 'cases')
        ->add_fields(Field::resolve(array(
            Field::make('text', 'case_subtitulo', __('Subtítulo', 'binomio')),
            Field::make('image', 'case_imagen', __('Imagen', 'binomio')),
            Field::make('rich_text', 'case_contenido', __('Contenido', 'binomio')),
            Field::make('select', 'case_categoria', __('Categoría', 'binomio'))
                ->set_options(array(
                    'sculpture' => __('Sculpture', 'binomio'),
                    'painting' => __('Painting', 'binomio'),
                    'illustration' => __('Illustration', 'binomio'),
                    'mural' => __('Mural', 'binomio'),
                    'other' => __('Other', 'binomio'),
                )),
            Field::make('set', 'case_tipo', __('Tipo', 'binomio'))
                ->set_options(array(
                    'branding' => __('Branding', 'binomio'),
                    'ux_ui' => __('UX/UI', 'binomio'),
                    'development' => __('Development', 'binomio'),
                    'website' => __('Website', 'binomio'),
                )),
            Field::make('text', 'case_ano', __('Año', 'binomio')),
            Field::make('complex', 'case_links', __('Links', 'binomio'))
                ->set_layout('tabbed-vertical')
                ->add_fields(Field::resolve(array(
                    Field::make('select', 'tipo', __('Tipo de link', 'binomio'))
                        ->set_options(array(
                            'web' => __('Web', 'binomio'),
                            'brandbook' => __('Brandbook', 'binomio'),
                            'other' => __('Otro', 'binomio'),
                        )),
                    Field::make('text', 'texto', __('Texto del link', 'binomio')),
                    Field::make('text', 'url', __('URL', 'binomio')),
                ))),
        )));
}

add_filter('use_block_editor_for_post_type', 'binomio_disable_gutenberg_for_cases', 10, 2);
function binomio_disable_gutenberg_for_cases($use_block_editor, $post_type) {
    if ($post_type === 'cases') {
        return false;
    }

    return $use_block_editor;
}

add_action('admin_init', 'binomio_remove_editor_support_for_cases');
function binomio_remove_editor_support_for_cases() {
    remove_post_type_support('cases', 'editor');
}
