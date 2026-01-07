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

<body <?php body_class(); ?>>
    <?php 
        $assets_url = get_stylesheet_directory_uri() . '/assets';
    ?>
    <?php wp_body_open(); ?>
    <div id="wrapper" class="hfeed">
        <nav id="header" role="navigation" <?php echo is_admin_bar_showing() ? 'style="top: 32px;"' : '' ?>>
            <a href="<?php echo home_url(); ?>" class="header-logo">
                <img 
                    src="<?php echo $assets_url; ?>/images/binomio-logo.png" 
                    alt="Binomio Studio Logo" 
                    class="logo"
                >
            </a>
            <div class="header-links">
                <a class="link" href="/artistas">Collections</a>
                <a class="link" href="/estudio">Archive</a>
                <a class="link" href="/contacto">About</a>
                <a class="link" href="/contacto">Contact</a>
            </div>
        </nav>
        <div id="container">
            <main id="content" role="main">