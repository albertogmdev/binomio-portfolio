<?php
/**
 * Template Name: Section-based
 * 
 * Template para páginas con componentes modulares
 */

get_header(); 

while ( have_posts() ) : the_post(); 
    
    // Obtener los componentes de la página
    $components = carbon_get_the_post_meta( 'crb_page_components' );
    
    if ( ! empty( $components ) ) {
        foreach ( $components as $component ) {
            // El tipo de componente está en _type
            $component_type = $component['_type'] ?? '';
            
            if ( $component_type ) {
                // Renderizar el componente usando el loader
                Binomio_Component_Loader::render_component( $component_type, $component );
            }
        }
    } else {
        // Si no hay componentes, mostrar el contenido por defecto
        ?>
        <div class="container">
            <article class="entry">
                <h1><?php the_title(); ?></h1>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
        <?php
    }
    
endwhile;

get_footer();
