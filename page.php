<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php
// Obtener los componentes de la página
$components = carbon_get_the_post_meta( 'crb_page_components' );

if ( ! empty( $components ) ) {
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
}
?>

<?php endwhile; endif; ?>
<?php get_footer(); ?>