<?php
/**
 * Registro de Custom Post Type: Proyectos
 */

use TranslatableCarbonFields\Fields\Field;
use Carbon_Fields\Container\Container;

add_action('init', 'binomio_register_post_type_proyectos');
function binomio_register_post_type_proyectos() {
    $labels = array(
        'name' => __('Proyectos', 'binomio'),
        'singular_name' => __('Proyecto', 'binomio'),
        'menu_name' => __('Proyectos', 'binomio'),
        'name_admin_bar' => __('Proyecto', 'binomio'),
        'add_new' => __('Añadir nuevo', 'binomio'),
        'add_new_item' => __('Añadir nuevo proyecto', 'binomio'),
        'new_item' => __('Nuevo proyecto', 'binomio'),
        'edit_item' => __('Editar proyecto', 'binomio'),
        'view_item' => __('Ver proyecto', 'binomio'),
        'all_items' => __('Todos los proyectos', 'binomio'),
        'search_items' => __('Buscar proyectos', 'binomio'),
        'not_found' => __('No se encontraron proyectos', 'binomio'),
        'not_found_in_trash' => __('No se encontraron proyectos en la papelera', 'binomio'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => false,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'projects'),
        'supports' => array(),
    );

    register_post_type('projects', $args);
}

add_action('init', 'binomio_register_taxonomy_division_for_proyectos');
function binomio_register_taxonomy_division_for_proyectos() {
    $labels = array(
        'name' => __('Divisiones', 'binomio'),
        'singular_name' => __('División', 'binomio'),
        'search_items' => __('Buscar divisiones', 'binomio'),
        'all_items' => __('Todas las divisiones', 'binomio'),
        'edit_item' => __('Editar división', 'binomio'),
        'update_item' => __('Actualizar división', 'binomio'),
        'add_new_item' => __('Añadir nueva división', 'binomio'),
        'new_item_name' => __('Nombre de la nueva división', 'binomio'),
        'menu_name' => __('División', 'binomio'),
    );

    register_taxonomy('division', array('projects'), array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => false,
        'hierarchical' => false,
        'query_var' => 'division',
        'rewrite' => false,
    ));

    if (!term_exists('studio', 'division')) {
        wp_insert_term('Studio', 'division', array('slug' => 'studio'));
    }

    if (!term_exists('artist', 'division')) {
        wp_insert_term('Artist', 'division', array('slug' => 'artist'));
    }
}

add_action('init', 'binomio_register_proyectos_division_rewrites', 20);
function binomio_register_proyectos_division_rewrites() {
    $project_archive_paths = array(
        binomio_get_projects_archive_path('artist', 'en'),
        binomio_get_projects_archive_path('artist', 'es'),
    );
    $studio_project_archive_paths = array(
        binomio_get_projects_archive_path('studio', 'en'),
        binomio_get_projects_archive_path('studio', 'es'),
    );

    foreach (array_unique($project_archive_paths) as $path) {
        binomio_add_localized_rewrite_rule($path . '/?$', 'index.php?post_type=projects&division=artist', 'top');
        binomio_add_localized_rewrite_rule($path . '/([^/]+)/?$', 'index.php?post_type=projects&name=$matches[1]&division=artist', 'top');
    }

    foreach (array_unique($studio_project_archive_paths) as $path) {
        binomio_add_localized_rewrite_rule($path . '/?$', 'index.php?post_type=projects&division=studio', 'top');
        binomio_add_localized_rewrite_rule($path . '/([^/]+)/?$', 'index.php?post_type=projects&name=$matches[1]&division=studio', 'top');
    }
}

add_filter('post_type_link', 'binomio_proyectos_division_permalink', 10, 2);
function binomio_proyectos_division_permalink($post_link, $post) {
    if ($post->post_type !== 'projects') {
        return $post_link;
    }

    $terms = wp_get_post_terms($post->ID, 'division');
    $division_slug = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->slug : 'artist';
    $post_language = binomio_get_post_language($post->ID);
    $archive_path = binomio_get_projects_archive_path($division_slug, $post_language);

    return home_url(user_trailingslashit($archive_path . '/' . $post->post_name));
}

add_action('pre_get_posts', 'binomio_filter_proyectos_by_division');
function binomio_filter_proyectos_by_division($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    $post_type = $query->get('post_type');
    $is_projects_query = ($post_type === 'projects') || (is_array($post_type) && in_array('projects', $post_type, true)) || $query->is_post_type_archive('projects');

    if (!$is_projects_query) {
        return;
    }

    $division = $query->get('division');

    if (empty($division)) {
        $request_path = binomio_get_request_path();

        if (preg_match('#^(' . preg_quote(binomio_get_projects_archive_path('artist', 'en'), '#') . '|' . preg_quote(binomio_get_projects_archive_path('artist', 'es'), '#') . ')(/|$)#', $request_path)) {
            $division = 'artist';
            $query->set('division', $division);
        } elseif (preg_match('#^(' . preg_quote(binomio_get_projects_archive_path('studio', 'en'), '#') . '|' . preg_quote(binomio_get_projects_archive_path('studio', 'es'), '#') . ')(/|$)#', $request_path)) {
            $division = 'studio';
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

add_action('template_redirect', 'binomio_validate_proyectos_division_url');
function binomio_validate_proyectos_division_url() {
    if (!is_singular('projects')) {
        return;
    }

    $post_id = get_queried_object_id();
    if (empty($post_id)) {
        return;
    }

    $terms = wp_get_post_terms($post_id, 'division');
    $actual_division = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->slug : 'artist';

    $request_path = binomio_get_request_path();

    if (preg_match('#^(' . preg_quote(binomio_get_projects_archive_path('studio', 'en'), '#') . '|' . preg_quote(binomio_get_projects_archive_path('studio', 'es'), '#') . ')/#', $request_path)) {
        $requested_division = 'studio';
    } elseif (preg_match('#^(' . preg_quote(binomio_get_projects_archive_path('artist', 'en'), '#') . '|' . preg_quote(binomio_get_projects_archive_path('artist', 'es'), '#') . ')/#', $request_path)) {
        $requested_division = 'artist';
    } else {
        return;
    }

    if ($requested_division !== $actual_division) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include get_query_template('404');
        exit;
    }
}

add_action('init', 'binomio_flush_proyectos_rewrites_once', 30);
function binomio_flush_proyectos_rewrites_once() {
    $rewrite_version = 'projects_division_rewrite_v8';

    if (get_option('binomio_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('binomio_rewrite_version', $rewrite_version);
}

add_action('carbon_fields_register_fields', 'binomio_register_proyectos_fields');
function binomio_register_proyectos_fields() {
    Container::make('post_meta', __('Destacado', 'binomio'))
        ->where('post_type', '=', 'projects')
        ->add_fields(Field::resolve(array(
            Field::make('checkbox', 'proyecto_featured_home', __('Mostrar en home (featured)', 'binomio')),
            Field::make('image', 'proyecto_featured_image', __('Imagen destacada', 'binomio')),
            Field::make('text', 'proyecto_featured_order', __('Orden en home', 'binomio')),
        )));

    Container::make('post_meta', __('Datos del Proyecto', 'binomio'))
        ->where('post_type', '=', 'projects')
        ->add_fields(Field::resolve(array(
            Field::make('text', 'proyecto_subtitulo', __('Subtítulo', 'binomio')),
            Field::make('rich_text', 'proyecto_descripcion', __('Descripción', 'binomio')),
            Field::make('complex', 'proyecto_links', __('Links', 'binomio'))
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
            Field::make('set', 'proyecto_tags', __('Tags del proyecto', 'binomio'))
                ->set_options(array(
                    'branding' => __('Branding', 'binomio'),
                    'ux_ui' => __('UX/UI', 'binomio'),
                    'development' => __('Development', 'binomio'),
                    'website' => __('Website', 'binomio'),
                )),
            Field::make('image', 'proyecto_portada', __('Portada', 'binomio')),
            Field::make('media_gallery', 'proyecto_full_assets', __('Galería Fullwidth', 'binomio')),
            Field::make('media_gallery', 'proyecto_galeria_assets', __('Galería', 'binomio')),
            Field::make('rich_text', 'proyecto_creditos', __('Créditos', 'binomio')),
            Field::make('association', 'proyecto_related', __('Relacionados', 'binomio'))
                ->set_types(array(
                    array(
                        'type' => 'post',
                        'post_type' => 'projects',
                    ),
                ))
                ->set_max(3),
        )));
}

add_filter('use_block_editor_for_post_type', 'binomio_disable_gutenberg_for_proyectos', 10, 2);
function binomio_disable_gutenberg_for_proyectos($use_block_editor, $post_type) {
    if ($post_type === 'projects') {
        return false;
    }

    return $use_block_editor;
}

add_action('admin_init', 'binomio_remove_editor_support_for_proyectos');
function binomio_remove_editor_support_for_proyectos() {
    remove_post_type_support('projects', 'editor');
}

add_action('init', 'binomio_migrate_proyectos_post_type_once', 40);
function binomio_migrate_proyectos_post_type_once() {
    $migration_version = 'projects_post_type_migration_v1';
    if (get_option('binomio_projects_migration_version') === $migration_version) {
        return;
    }

    global $wpdb;
    $wpdb->update(
        $wpdb->posts,
        array('post_type' => 'projects'),
        array('post_type' => 'proyectos'),
        array('%s'),
        array('%s')
    );

    update_option('binomio_projects_migration_version', $migration_version);
}