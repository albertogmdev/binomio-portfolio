<?php
/**
 * Tags — CPT gestionable desde el admin de WP.
 *
 * Las tags son un Custom Post Type propio (`project_tag`) para poder COMPARTIRLAS
 * entre proyectos y cases (y cualquier componente que las use). El cliente las
 * gestiona desde el admin SIN tocar código ni desplegar nada.
 *
 * IMPORTANTE (timing): Carbon Fields dispara `carbon_fields_register_fields`
 * en `init` con prioridad 0. Por eso este CPT se registra en `init` con
 * prioridad -10: así ya existe cuando los campos `set` piden sus opciones.
 *
 * Robustez frente a borrados:
 *  - En cada componente se guarda el SLUG de la tag (no el ID).
 *  - Al pintar en el front se comprueba que la tag siga existiendo:
 *    `binomio_get_tag_label()` devuelve '' si la tag ya no existe, y las
 *    plantillas simplemente NO la pintan. Nada se rompe.
 *
 * Para añadir/quitar tags: Admin de WP → Tags.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BINOMIO_TAG_POST_TYPE', 'project_tag');

/**
 * Registra el CPT de tags. Prioridad -10 para que exista antes de que Carbon
 * Fields registre los campos (init, prioridad 0).
 */
add_action('init', 'binomio_register_tag_cpt', -10);
function binomio_register_tag_cpt() {
    $labels = array(
        'name'               => __('Tags', 'binomio'),
        'singular_name'      => __('Tag', 'binomio'),
        'menu_name'          => __('Tags', 'binomio'),
        'add_new'            => __('Añadir nueva', 'binomio'),
        'add_new_item'       => __('Añadir nueva tag', 'binomio'),
        'new_item'           => __('Nueva tag', 'binomio'),
        'edit_item'          => __('Editar tag', 'binomio'),
        'view_item'          => __('Ver tag', 'binomio'),
        'all_items'          => __('Todas las tags', 'binomio'),
        'search_items'       => __('Buscar tags', 'binomio'),
        'not_found'          => __('No se encontraron tags', 'binomio'),
        'not_found_in_trash' => __('No se encontraron tags en la papelera', 'binomio'),
    );

    register_post_type(BINOMIO_TAG_POST_TYPE, array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,
        'menu_icon'           => 'dashicons-tag',
        'menu_position'       => 26,
        'supports'            => array('title'),
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
    ));

    binomio_seed_default_tags();
}

/**
 * Siembra (una sola vez) las tags por defecto para no romper el contenido ya
 * guardado con los slugs antiguos. Se ejecuta solo una vez: si el cliente borra
 * luego una de estas tags, NO se vuelve a recrear.
 */
function binomio_seed_default_tags() {
    if (get_option('binomio_tag_posts_seeded') === 'yes') {
        return;
    }

    $defaults = array(
        'branding'    => __('Branding', 'binomio'),
        'ux_ui'       => __('UX/UI', 'binomio'),
        'development' => __('Development', 'binomio'),
        'website'     => __('Website', 'binomio'),
    );

    foreach ($defaults as $slug => $name) {
        if (!get_page_by_path($slug, OBJECT, BINOMIO_TAG_POST_TYPE)) {
            wp_insert_post(array(
                'post_type'   => BINOMIO_TAG_POST_TYPE,
                'post_title'  => $name,
                'post_name'   => $slug,
                'post_status' => 'publish',
            ));
        }
    }

    update_option('binomio_tag_posts_seeded', 'yes');
}

/**
 * Devuelve el mapa de tags disponibles (slug => nombre) leído del CPT.
 *
 * Se usa para poblar las opciones de los campos `set` de los componentes.
 *
 * @return array<string,string>
 */
function binomio_get_tag_options() {
    static $cache = null;
    if (is_array($cache)) {
        return $cache;
    }

    // Si el CPT aún no está registrado, devolvemos vacío SIN cachear, para que
    // una llamada posterior (cuando ya exista) sí obtenga las opciones.
    if (!post_type_exists(BINOMIO_TAG_POST_TYPE)) {
        return array();
    }

    $tag_posts = get_posts(array(
        'post_type'        => BINOMIO_TAG_POST_TYPE,
        'post_status'      => 'publish',
        'numberposts'      => -1,
        'orderby'          => 'title',
        'order'            => 'ASC',
        'suppress_filters' => false,
    ));

    $options = array();
    foreach ($tag_posts as $tag_post) {
        $options[$tag_post->post_name] = $tag_post->post_title;
    }

    $cache = $options;

    return $cache;
}

/**
 * Devuelve el nombre legible de una tag a partir de su slug.
 *
 * Si la tag ya no existe en la taxonomía (se borró), devuelve '' para que las
 * plantillas no la pinten. Así el front nunca muestra tags huérfanas.
 *
 * @param string $slug Slug del término guardado en el componente.
 * @return string Nombre de la tag, o '' si ya no existe.
 */
function binomio_get_tag_label($slug) {
    $slug = (string) $slug;
    if ($slug === '') {
        return '';
    }

    $options = binomio_get_tag_options();

    return isset($options[$slug]) ? $options[$slug] : '';
}
