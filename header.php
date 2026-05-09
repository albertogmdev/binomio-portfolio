<!DOCTYPE html>
<html 
    <?php language_attributes(); ?> 
    <?php echo is_admin_bar_showing() ? 'style="margin-top: 32px;"' : '' ?>
>   

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <?php wp_head(); ?>
</head>

<body <?php body_class(is_front_page() ? 'front-page' : ''); ?>>
    <?php 
        $assets_url = get_stylesheet_directory_uri() . '/assets';
        $collections_url = function_exists('binomio_get_localized_page_url')
            ? binomio_get_localized_page_url(
                array(
                    'es' => array('artistas'),
                    'en' => array('artists', 'collections'),
                ),
                '/artistas/'
            )
            : home_url('/artistas/');
        $archive_url = function_exists('binomio_get_localized_page_url')
            ? binomio_get_localized_page_url(
                array(
                    'es' => array('estudio'),
                    'en' => array('studio'),
                ),
                '/estudio/'
            )
            : home_url('/estudio/');
        $about_url = function_exists('binomio_get_localized_page_url')
            ? binomio_get_localized_page_url(
                array(
                    'es' => array('sobre-mi', 'sobre-nosotros', 'acerca-de', 'about'),
                    'en' => array('about'),
                ),
                '/about/'
            )
            : home_url('/about/');
        $contact_url = function_exists('binomio_get_localized_page_url')
            ? binomio_get_localized_page_url(
                array(
                    'es' => array('contacto'),
                    'en' => array('contact'),
                ),
                '/contacto/'
            )
            : home_url('/contacto/');
    ?>
    <?php wp_body_open(); ?>
    <div id="wrapper" class="hfeed">
        <!-- Mobile menu backdrop -->
        <div class="mobile-menu-backdrop"></div>
        
        <!-- Mobile menu -->
        <div class="mobile-menu">
            <div class="mobile-menu-content">
                <?php 
                    // Determinar si es studio o artist
                    $is_studio = is_studio() || (isset($_GET['studio_section']) && $_GET['studio_section'] == '1');
                    $menu_location = $is_studio ? 'studio_menu' : 'artist_menu';
                    
                    // Mostrar el menú correspondiente
                    if (is_studio() || is_artist()) {
                        wp_nav_menu(array(
                            'theme_location' => $menu_location,
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                    } else {
                        wp_nav_menu(array(
                            'theme_location' => 'studio_menu',
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                        wp_nav_menu(array(
                            'theme_location' => 'artist_menu',
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                    }
                ?>
            </div>
        </div>

        <nav id="header" role="navigation" <?php echo is_admin_bar_showing() ? 'style="top: 32px;"' : '' ?>>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
                <span class="icon icon-bnomio"></span>
                <span class="icon icon-bnomiostudio"></span>
            </a>
            <button class="header-burger icon icon-burger"></button>
            <div class="header-links">
                <?php 
                    // Determinar si es studio o artist
                    $is_studio = is_studio() || (isset($_GET['studio_section']) && $_GET['studio_section'] == '1');
                    $menu_location = $is_studio ? 'studio_menu' : 'artist_menu';
                    
                    // Mostrar el menú correspondiente
                    if (is_studio() || is_artist()) {
                        wp_nav_menu(array(
                            'theme_location' => $menu_location,
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                    } else {
                        wp_nav_menu(array(
                            'theme_location' => 'studio_menu',
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                        wp_nav_menu(array(
                            'theme_location' => 'artist_menu',
                            'container' => false,
                            'fallback_cb' => 'binomio_menu_fallback',
                            'link_before' => '<span class="link">',
                            'link_after' => '</span>',
                        ));
                    }
                ?>
            </div>
        </nav>
        <div id="container">
            <main id="content" role="main">