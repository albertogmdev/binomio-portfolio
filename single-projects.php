<?php

/**
 * Single template para CPT Proyectos
 *
 * Renderiza la página mediante el constructor de componentes (crb_page_components),
 * el mismo builder usado en las páginas.
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id = get_the_ID();

        // Obtener los componentes del proyecto.
        $components = carbon_get_the_post_meta('crb_page_components');

        // Fallback Polylang: si el idioma secundario no tiene componentes,
        // leer del post en el idioma por defecto.
        if (empty($components) && function_exists('pll_get_post') && function_exists('pll_default_language')) {
            $source_id = pll_get_post($post_id, pll_default_language());
            if ($source_id && $source_id !== $post_id) {
                $components = carbon_get_post_meta($source_id, 'crb_page_components');
            }
        }
?>
        <div id="project-<?php echo esc_attr($post_id); ?>" class="page-project">
            <?php
            if (!empty($components)) {
                foreach ($components as $component) {
                    $component_type = $component['_type'] ?? '';

                    if ($component_type) {
                        Binomio_Component_Loader::render_component($component_type, $component);
                    }
                }
            }
            ?>
        </div>
<?php
    endwhile;
endif;

get_footer();
