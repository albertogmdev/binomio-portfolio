<!DOCTYPE html>
<html 
    <?php language_attributes(); ?> 
    <?php blankslate_schema_type(); ?>
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
        <nav id="header" role="navigation" <?php echo is_admin_bar_showing() ? 'style="top: 32px;"' : '' ?>>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
                <span class="icon icon-bnomio"></span>
                <span class="icon icon-bnomiostudio"></span>
            </a>
            <button class="header-burger icon icon-burger"></button>
            <div class="header-links">
                <a class="link" href="<?php echo esc_url($collections_url); ?>"><?php echo esc_html__('Collections', 'binomio'); ?></a>
                <a class="link" href="<?php echo esc_url($archive_url); ?>"><?php echo esc_html__('Archive', 'binomio'); ?></a>
                <a class="link" href="<?php echo esc_url($about_url); ?>"><?php echo esc_html__('About', 'binomio'); ?></a>
                <a class="link" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html__('Contact', 'binomio'); ?></a>
            </div>
        </nav>
        <div id="container">
            <main id="content" role="main">