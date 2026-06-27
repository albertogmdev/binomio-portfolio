<?php
/**
 * Main Page Component Template
 *
 * Renderiza únicamente el hero half elegido (studio o artist).
 * No se pinta el otro half ni el nointeract-zone.
 * No se hacen queries innecesarias al lado no seleccionado.
 */

$section = isset($component['main_page_section']) ? $component['main_page_section'] : 'studio';
$section = in_array($section, array('studio', 'artist'), true) ? $section : 'studio';

$projects_post_type = post_type_exists('projects') ? 'projects' : 'proyectos';

$get_featured_projects = function ($division_slug) use ($projects_post_type) {
    $query = new WP_Query(array(
        'post_type'      => $projects_post_type,
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'lang'           => '',
        'tax_query'      => array(
            array(
                'taxonomy' => 'division',
                'field'    => 'slug',
                'terms'    => $division_slug,
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => 'proyecto_featured_home',
                'value'   => array('1', 'true', 'yes', 'on'),
                'compare' => 'IN',
            ),
        ),
    ));

    $projects = $query->posts;

    usort($projects, function ($a, $b) {
        $a_order = trim((string) carbon_get_post_meta($a->ID, 'proyecto_featured_order'));
        $b_order = trim((string) carbon_get_post_meta($b->ID, 'proyecto_featured_order'));
        $a_has   = $a_order !== '';
        $b_has   = $b_order !== '';
        if ($a_has && !$b_has) return -1;
        if (!$a_has && $b_has) return 1;
        if ($a_has && $b_has) {
            $an = is_numeric($a_order) ? (int) $a_order : PHP_INT_MAX;
            $bn = is_numeric($b_order) ? (int) $b_order : PHP_INT_MAX;
            if ($an !== $bn) return $an <=> $bn;
        }
        return strcasecmp(get_the_title($a->ID), get_the_title($b->ID));
    });

    return $projects;
};

$get_project_permalink = function ($post_id) {
    if (function_exists('pll_get_post') && function_exists('pll_current_language')) {
        $lang           = pll_current_language();
        $translated_id  = pll_get_post($post_id, $lang);
        if ($translated_id) return get_permalink($translated_id);
        $default_lang = function_exists('pll_default_language') ? pll_default_language() : 'es';
        if ($lang !== $default_lang) {
            $url    = get_permalink($post_id);
            $base   = untrailingslashit(site_url());
            $prefix = $base . '/' . $default_lang . '/';
            if (strpos($url, $prefix) === 0) {
                return $base . '/' . $lang . '/' . substr($url, strlen($prefix));
            }
        }
    }
    return get_permalink($post_id);
};

// Solo se hacen queries para el section elegido
$featured_projects = $get_featured_projects($section);

if ($section === 'studio') {
    $projects_url = function_exists('binomio_get_projects_archive_url')
        ? binomio_get_projects_archive_url('studio')
        : home_url('/studio/projects/');
    $about_url = function_exists('binomio_get_localized_page_url')
        ? binomio_get_localized_page_url(
            array(
                'es' => array('sobre-mi', 'sobre-nosotros', 'acerca-de', 'about'),
                'en' => array('about'),
            ),
            '/about/'
        )
        : home_url('/about/');
    $intro_text = bnm_t('home_studio_intro', 'Nomad Design Studio');

    $stickers = array();
    if (post_type_exists('stickers')) {
        $stickers_query = new WP_Query(array(
            'post_type'      => 'stickers',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'division',
                    'field'    => 'slug',
                    'terms'    => 'studio',
                ),
            ),
            'meta_query'     => array(
                array(
                    'key'     => 'sticker_show_in_home',
                    'value'   => array('1', 'true', 'yes', 'on'),
                    'compare' => 'IN',
                ),
            ),
        ));
        $stickers = $stickers_query->posts;
    }
} else {
    $projects_url = function_exists('binomio_get_projects_archive_url')
        ? binomio_get_projects_archive_url('artist')
        : home_url('/projects/');
    $intro_text = bnm_t('home_artist_intro', 'Creative Consultant');
    $stickers   = array();
}
?>

<section class="bnomio-hero bnomio-hero--static">

    <?php if ($section === 'studio') : ?>

    <div class="bnomio-hero--half studio-hero theme--studio enabled entered">
        <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
            alt="bnomio Studio"
            class="hero-image"
            style="left: 0px; right: unset; opacity: 1;">
        <div class="hero-link">
            <p class="link-text"><?php echo nl2br(esc_html($intro_text)); ?></p>
            <span class="link-icon icon icon-bnomiostudio"></span>
            <button id="enter-studio" class="link-button button"><?php echo esc_html(bnm_t('home_studio_enter', 'Enter')); ?></button>
        </div>
        <div class="hero-content">
            <?php if (!empty($stickers)) : ?>
            <div class="studio-stickers" aria-hidden="true">
                <?php foreach ($stickers as $sticker) : ?>
                    <?php
                    $sticker_image_id  = (int) carbon_get_post_meta($sticker->ID, 'sticker_image');
                    $sticker_image_url = $sticker_image_id > 0 ? wp_get_attachment_url($sticker_image_id) : '';
                    if (empty($sticker_image_url)) continue;

                    $desktop_size    = (float) carbon_get_post_meta($sticker->ID, 'sticker_size_desktop');
                    $mobile_size     = (float) carbon_get_post_meta($sticker->ID, 'sticker_size_mobile');
                    $initial_x       = (float) carbon_get_post_meta($sticker->ID, 'sticker_initial_x');
                    $initial_y       = (float) carbon_get_post_meta($sticker->ID, 'sticker_initial_y');
                    $initial_x_mobile = (float) carbon_get_post_meta($sticker->ID, 'sticker_initial_x_mobile');
                    $initial_y_mobile = (float) carbon_get_post_meta($sticker->ID, 'sticker_initial_y_mobile');
                    $rotation        = (float) carbon_get_post_meta($sticker->ID, 'sticker_rotation');
                    $z_index         = (int) carbon_get_post_meta($sticker->ID, 'sticker_z_index');

                    $desktop_size     = $desktop_size > 0 ? $desktop_size : 180;
                    $mobile_size      = $mobile_size > 0 ? $mobile_size : 120;
                    $initial_x        = $initial_x !== 0.0 ? $initial_x : 50;
                    $initial_y        = $initial_y !== 0.0 ? $initial_y : 50;
                    $initial_x_mobile = $initial_x_mobile !== 0.0 ? $initial_x_mobile : $initial_x;
                    $initial_y_mobile = $initial_y_mobile !== 0.0 ? $initial_y_mobile : $initial_y;
                    $z_index          = $z_index > 0 ? $z_index : 1;
                    ?>
                    <div
                        class="studio-sticker"
                        data-sticker-id="<?php echo esc_attr((string) $sticker->ID); ?>"
                        style="--sticker-size-desktop: <?php echo esc_attr((string) $desktop_size); ?>px; --sticker-size-mobile: <?php echo esc_attr((string) $mobile_size); ?>px; --sticker-x: <?php echo esc_attr((string) $initial_x); ?>%; --sticker-y: <?php echo esc_attr((string) $initial_y); ?>%; --sticker-x-mobile: <?php echo esc_attr((string) $initial_x_mobile); ?>%; --sticker-y-mobile: <?php echo esc_attr((string) $initial_y_mobile); ?>%; --sticker-rotation: <?php echo esc_attr((string) $rotation); ?>deg; --sticker-z: <?php echo esc_attr((string) $z_index); ?>; touch-action: none;"
                    >
                        <img src="<?php echo esc_url($sticker_image_url); ?>" alt="<?php echo esc_attr(get_the_title($sticker->ID)); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
                alt="Bnomio Studio"
                class="content-image">
            <div class="content-projectlist">
                <h2 class="list-title"><?php echo esc_html(bnm_t('home_studio_projects', 'Recent Projects')); ?></h2>
                <ul class="projectlist">
                    <?php foreach ($featured_projects as $project) : ?>
                        <li class="project-item">
                            <a href="<?php echo esc_url($get_project_permalink($project->ID)); ?>" class="link"><?php echo esc_html(get_the_title($project->ID)); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url($projects_url); ?>" class="button"><?php echo esc_html(bnm_t('home_studio_all', 'All projects')); ?></a>
            </div>
            <span class="scroll-indicator">Scroll <span class="icon icon-arrowright"></span></span>
            <div class="content-projects">
                <?php foreach (array_slice($featured_projects, 0, 6) as $project) : ?>
                    <?php
                    $project_custom_title   = tcf_meta($project->ID, 'proyecto_titulo', 'proyecto_translations');
                    $project_title          = $project_custom_title !== '' ? $project_custom_title : get_the_title($project->ID);
                    $project_permalink      = $get_project_permalink($project->ID);
                    $project_featured_image = carbon_get_post_meta($project->ID, 'proyecto_featured_image');
                    $project_portada        = carbon_get_post_meta($project->ID, 'proyecto_portada');
                    $project_image_id       = !empty($project_featured_image) ? $project_featured_image : $project_portada;
                    $project_image_url      = !empty($project_image_id) ? wp_get_attachment_url($project_image_id) : '';
                    $project_tags           = carbon_get_post_meta($project->ID, 'proyecto_tags');
                    $project_subtitle       = implode(' · ', binomio_get_tag_labels($project_tags));
                    $project_description    = tcf_meta($project->ID, 'proyecto_descripcion', 'proyecto_translations');
                    $project_description_text = wp_trim_words(wp_strip_all_tags((string) $project_description), 16, '...');
                    $project_aspect = carbon_get_post_meta($project->ID, 'proyecto_featured_aspect');
                    $project_width  = carbon_get_post_meta($project->ID, 'proyecto_featured_width');
                    $card_classes = 'collection-card collection-card--displayed';
                    $card_style_parts = [];
                    if (!empty($project_aspect)) { $card_classes .= ' collection-card--custom-aspect'; $card_style_parts[] = '--card-aspect-ratio: ' . esc_attr($project_aspect); }
                    if (!empty($project_width))  { $card_classes .= ' collection-card--custom-width';  $card_style_parts[] = '--card-width: ' . esc_attr($project_width) . 'px'; }
                    $card_style = !empty($card_style_parts) ? implode('; ', $card_style_parts) : '';
                    ?>
                    <div class="<?php echo esc_attr($card_classes); ?>"<?php echo $card_style ? ' style="' . $card_style . '"' : ''; ?>>
                        <a href="<?php echo esc_url($project_permalink); ?>">
                            <?php if (!empty($project_image_url)) : ?>
                                <img class="card-image" src="<?php echo esc_url($project_image_url); ?>" alt="<?php echo esc_attr($project_title); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="card-info">
                            <h3 class="item-title"><?php echo esc_html($project_title); ?></h3>
                            <?php if (!empty($project_subtitle)) : ?>
                                <p class="item-subtitle"><?php echo esc_html($project_subtitle); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($project_description_text)) : ?>
                                <p class="item-description"><?php echo esc_html($project_description_text); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($project_permalink); ?>" class="button item-button"><?php echo esc_html(bnm_t('home_see_project', 'See project')); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="about-card theme--artist">
                    <div class="card-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/thestudio.jpg" alt="The Studio">
                    </div>
                    <div class="card-info">
                        <h3 class="item-title text-h1"><?php echo nl2br(esc_html(bnm_t('home_studio_name', "The\nStudio"))); ?></h3>
                        <a href="https://www.instagram.com/bnomio.studio" class="item-link">@bnomio.studio</a>
                        <a href="<?php echo esc_url($about_url); ?>" class="button item-button"><?php echo esc_html(bnm_t('home_studio_about', 'About')); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-footer">
            <div class="footer-main">
                <div class="footer-socials">
                    <a href="https://www.instagram.com/bnomio.studio" target="_blank" rel="noopener noreferrer" class="social-link">
                        <span class="icon icon-instagram"></span>
                        <p class="social-text">@BNOMIO.STUDIO</p>
                    </a>
                </div>
                <div class="footer-info">
                    <p><?php echo esc_html(bnm_t('footer_copyright', 'BNOMIO | COPYRIGHT 2025 ALL RIGHTS RESERVED')); ?></p>
                </div>
            </div>
        </div>

        <a class="back-tab static-back" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Back">
            <span class="back-tab-preview">
                <span class="icon icon-arrowleft"></span>
            </span>
        </a>
    </div>

    <?php else : ?>

    <div class="bnomio-hero--half artist-hero theme--artist enabled entered">
        <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/artist-head.png"
            alt="bnomio Artist"
            class="hero-image"
            style="right: 0px; left: unset; opacity: 1;">
        <div class="hero-link">
            <p class="link-text"><?php echo nl2br(esc_html($intro_text)); ?></p>
            <span class="link-icon icon icon-bnomio"></span>
            <button id="enter-artist" class="link-button button"><?php echo esc_html(bnm_t('home_artist_enter', 'Coming soon')); ?></button>
        </div>
        <div class="hero-content">
            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
                alt="Bnomio Artist"
                class="content-image">
            <div class="content-projectlist">
                <h2 class="list-title"><?php echo esc_html(bnm_t('home_artist_projects', 'Recent Projects')); ?></h2>
                <ul class="projectlist">
                    <?php foreach ($featured_projects as $project) : ?>
                        <li class="project-item">
                            <a href="<?php echo esc_url($get_project_permalink($project->ID)); ?>" class="link"><?php echo esc_html(get_the_title($project->ID)); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url($projects_url); ?>" class="button"><?php echo esc_html(bnm_t('home_artist_all', 'All projects')); ?></a>
            </div>
            <span class="scroll-indicator">Scroll <span class="icon icon-arrowright"></span></span>
            <div class="content-projects">
                <?php foreach (array_slice($featured_projects, 0, 6) as $project) : ?>
                    <?php
                    $project_custom_title   = tcf_meta($project->ID, 'proyecto_titulo', 'proyecto_translations');
                    $project_title          = $project_custom_title !== '' ? $project_custom_title : get_the_title($project->ID);
                    $project_permalink      = $get_project_permalink($project->ID);
                    $project_featured_image = carbon_get_post_meta($project->ID, 'proyecto_featured_image');
                    $project_portada        = carbon_get_post_meta($project->ID, 'proyecto_portada');
                    $project_image_id       = !empty($project_featured_image) ? $project_featured_image : $project_portada;
                    $project_image_url      = !empty($project_image_id) ? wp_get_attachment_url($project_image_id) : '';
                    $project_tags           = carbon_get_post_meta($project->ID, 'proyecto_tags');
                    $project_subtitle       = implode(' · ', binomio_get_tag_labels($project_tags));
                    $project_description    = tcf_meta($project->ID, 'proyecto_descripcion', 'proyecto_translations');
                    $project_description_text = wp_trim_words(wp_strip_all_tags((string) $project_description), 16, '...');
                    $project_aspect = carbon_get_post_meta($project->ID, 'proyecto_featured_aspect');
                    $project_width  = carbon_get_post_meta($project->ID, 'proyecto_featured_width');
                    $card_classes = 'collection-card collection-card--displayed';
                    $card_style_parts = [];
                    if (!empty($project_aspect)) { $card_classes .= ' collection-card--custom-aspect'; $card_style_parts[] = '--card-aspect-ratio: ' . esc_attr($project_aspect); }
                    if (!empty($project_width))  { $card_classes .= ' collection-card--custom-width';  $card_style_parts[] = '--card-width: ' . esc_attr($project_width) . 'px'; }
                    $card_style = !empty($card_style_parts) ? implode('; ', $card_style_parts) : '';
                    ?>
                    <div class="<?php echo esc_attr($card_classes); ?>"<?php echo $card_style ? ' style="' . $card_style . '"' : ''; ?>>
                        <a href="<?php echo esc_url($project_permalink); ?>">
                            <?php if (!empty($project_image_url)) : ?>
                                <img class="card-image" src="<?php echo esc_url($project_image_url); ?>" alt="<?php echo esc_attr($project_title); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="card-info">
                            <h3 class="item-title"><?php echo esc_html($project_title); ?></h3>
                            <?php if (!empty($project_subtitle)) : ?>
                                <p class="item-subtitle"><?php echo esc_html($project_subtitle); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($project_description_text)) : ?>
                                <p class="item-description"><?php echo esc_html($project_description_text); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($project_permalink); ?>" class="button item-button"><?php echo esc_html(bnm_t('home_see_project', 'See project')); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hero-footer">
            <div class="footer-main">
                <div class="footer-socials">
                    <a href="https://www.instagram.com/bnomio" target="_blank" rel="noopener noreferrer" class="social-link">
                        <span class="icon icon-instagram"></span>
                        <p class="social-text">@BNOMIO</p>
                    </a>
                </div>
                <div class="footer-info">
                    <p><?php echo esc_html(bnm_t('footer_copyright', 'BNOMIO | COPYRIGHT 2025 ALL RIGHTS RESERVED')); ?></p>
                </div>
            </div>
        </div>

        <a class="back-tab static-back" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Back">
            <span class="back-tab-preview">
                <span class="icon icon-arrowleft"></span>
            </span>
        </a>
    </div>

    <?php endif; ?>

</section>
