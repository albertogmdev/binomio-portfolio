<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php
// Obtener los componentes de la página
$components = carbon_get_the_post_meta( 'crb_page_components' );

// Si el post actual (idioma secundario, duplicado Polylang) no tiene componentes,
// leer del post en el idioma por defecto, que es donde se configura el constructor.
if ( empty( $components ) && function_exists( 'pll_get_post' ) && function_exists( 'pll_default_language' ) ) {
    $source_id = pll_get_post( get_the_ID(), pll_default_language() );
    if ( $source_id && $source_id !== get_the_ID() ) {
        $components = carbon_get_post_meta( $source_id, 'crb_page_components' );
    }
}
?>

<div id="page-<?php the_ID(); ?>" class="page-builder">
    <?php if ( ! empty( $components ) ) {
        // Si hay componentes, renderizarlos
        foreach ( $components as $component ) {
            $component_type = $component['_type'] ?? '';

            if ( $component_type ) {
                Binomio_Component_Loader::render_component( $component_type, $component );
            }
        }
    } else {
        // Si no hay componentes, mostrar el layout tradicional
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="header">
                <h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
            </header>
            <div class="entry-content" itemprop="mainContentOfPage">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) ); } ?>
                <?php the_content(); ?>
                <div class="entry-links"><?php wp_link_pages(); ?></div>
            </div>
        </article>
        <?php if ( comments_open() && !post_password_required() ) { comments_template( '', true ); } ?>
        <?php
    } ?>
</div>

<?php endwhile; endif; ?>
<?php get_footer(); ?>