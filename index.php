<?php
get_header();

$projects_post_type = post_type_exists('projects') ? 'projects' : 'proyectos';

$get_featured_projects_by_division = function ($division_slug) use ($projects_post_type) {
    $query = new WP_Query(array(
        'post_type' => $projects_post_type,
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'lang' => '',
        'tax_query' => array(
            array(
                'taxonomy' => 'division',
                'field' => 'slug',
                'terms' => $division_slug,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => 'proyecto_featured_home',
                'value' => array('1', 'true', 'yes', 'on'),
                'compare' => 'IN',
            ),
        ),
    ));

    $projects = $query->posts;

    usort($projects, function ($first_project, $second_project) {
        $first_order_raw = carbon_get_post_meta($first_project->ID, 'proyecto_featured_order');
        $second_order_raw = carbon_get_post_meta($second_project->ID, 'proyecto_featured_order');

        $first_order_value = trim((string) $first_order_raw);
        $second_order_value = trim((string) $second_order_raw);

        $first_has_order = $first_order_value !== '';
        $second_has_order = $second_order_value !== '';

        if ($first_has_order && !$second_has_order) {
            return -1;
        }

        if (!$first_has_order && $second_has_order) {
            return 1;
        }

        if ($first_has_order && $second_has_order) {
            $first_order_number = is_numeric($first_order_value) ? (int) $first_order_value : PHP_INT_MAX;
            $second_order_number = is_numeric($second_order_value) ? (int) $second_order_value : PHP_INT_MAX;

            if ($first_order_number !== $second_order_number) {
                return $first_order_number <=> $second_order_number;
            }
        }

        return strcasecmp(get_the_title($first_project->ID), get_the_title($second_project->ID));
    });

    return $projects;
};

$studio_featured_projects = $get_featured_projects_by_division('studio');
$artist_featured_projects = $get_featured_projects_by_division('artist');
$artist_projects_url = function_exists('binomio_get_projects_archive_url') ? binomio_get_projects_archive_url('artist') : home_url('/projects/');
$studio_projects_url = function_exists('binomio_get_projects_archive_url') ? binomio_get_projects_archive_url('studio') : home_url('/studio/projects/');
$studio_about_url = function_exists('binomio_get_localized_page_url')
    ? binomio_get_localized_page_url(
        array(
            'es' => array('sobre-mi', 'sobre-nosotros', 'acerca-de', 'about'),
            'en' => array('about'),
        ),
        '/about/'
    )
    : home_url('/about/');
$artist_intro_text = __('Creative Consultant', 'binomio');
$studio_intro_text = __('Nomad Design Studio', 'binomio');

$get_project_permalink = function ($post_id) {
    if (function_exists('pll_get_post') && function_exists('pll_current_language')) {
        $lang = pll_current_language();
        $translated_id = pll_get_post($post_id, $lang);
        if ($translated_id) {
            return get_permalink($translated_id);
        }
        // No translation exists: swap the language prefix in the URL.
        // site_url() is not modified by Polylang so it gives a clean base.
        $default_lang = function_exists('pll_default_language') ? pll_default_language() : 'es';
        if ($lang !== $default_lang) {
            $url = get_permalink($post_id);
            $base = untrailingslashit(site_url());
            $prefix = $base . '/' . $default_lang . '/';
            if (strpos($url, $prefix) === 0) {
                return $base . '/' . $lang . '/' . substr($url, strlen($prefix));
            }
        }
    }
    return get_permalink($post_id);
};
?>


<section class="bnomio-hero">
    <div class="bnomio-hero--half artist-hero theme--artist">
        <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
            alt="bnomio Studio"
            class="hero-image"
            style="right: -1000px;">
        <div class="hero-link">
            <p class="link-text"><?php echo nl2br(esc_html($artist_intro_text)); ?></p>
            <span class="link-icon icon icon-bnomio"></span>
            <button id="enter-artist" class="link-button button"><?php echo esc_html__('Coming soon', 'binomio'); ?></button>
        </div>
        <div class="hero-content">
            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
                alt="Bnomio Artist"
                class="content-image">
            <div class="content-projectlist">
                <h2 class="list-title"><?php echo esc_html__('Recent Projects', 'binomio'); ?></h2>
                <ul class="projectlist">
                    <?php foreach ($artist_featured_projects as $project) : ?>
                        <li class="project-item">
                            <a href="<?php echo esc_url($get_project_permalink($project->ID)); ?>" class="link"><?php echo esc_html(get_the_title($project->ID)); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url($artist_projects_url); ?>" class="button"><?php echo esc_html__('All projects', 'binomio'); ?></a>
            </div>
            <div class="content-projects">
                <?php foreach (array_slice($artist_featured_projects, 0, 6) as $project) : ?>
                    <?php
                    $project_title = get_the_title($project->ID);
                    $project_permalink = $get_project_permalink($project->ID);
                    $project_featured_image = carbon_get_post_meta($project->ID, 'proyecto_featured_image');
                    $project_portada = carbon_get_post_meta($project->ID, 'proyecto_portada');
                    $project_image_id = !empty($project_featured_image) ? $project_featured_image : $project_portada;
                    $project_image_url = !empty($project_image_id) ? wp_get_attachment_url($project_image_id) : '';
                    $project_tags = carbon_get_post_meta($project->ID, 'proyecto_tags');
                    $project_subtitle = is_array($project_tags) ? implode(' · ', $project_tags) : '';
                    $project_description = tcf_meta($project->ID, 'proyecto_descripcion', 'proyecto_translations');
                    $project_description_text = wp_trim_words(wp_strip_all_tags((string) $project_description), 16, '...');
                    ?>
                    <div class="collection-card collection-card--displayed">
                        <a href="<?php echo esc_url($project_permalink); ?>">
                            <?php if (!empty($project_image_url)) : ?>
                                <img
                                    class="card-image"
                                    src="<?php echo esc_url($project_image_url); ?>"
                                    alt="<?php echo esc_attr($project_title); ?>">
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
                            <a href="<?php echo esc_url($project_permalink); ?>" class="button item-button"><?php echo esc_html__('See project', 'binomio'); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="exit-button button"><?php echo esc_html__('Go Back', 'binomio'); ?></button>
        </div>
    </div>
    <div class="nointeract-zone"></div>
    <div class="bnomio-hero--half studio-hero theme--studio">
        <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
            alt="bnomio Studio"
            class="hero-image">
        <div class="hero-link">
            <p class="link-text"><?php echo nl2br(esc_html($studio_intro_text)); ?></p>
            <span class="link-icon icon icon-bnomiostudio"></span>
            <button id="enter-studio" class="link-button button"><?php echo esc_html__('Enter', 'binomio'); ?></button>
        </div>
        <div class="hero-content">
            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/studio-head.png"
                alt="Bnomio Studio"
                class="content-image">
            <div class="content-projectlist">
                <h2 class="list-title"><?php echo esc_html__('Recent Projects', 'binomio'); ?></h2>
                <ul class="projectlist">
                    <?php foreach ($studio_featured_projects as $project) : ?>
                        <li class="project-item">
                            <a href="<?php echo esc_url($get_project_permalink($project->ID)); ?>" class="link"><?php echo esc_html(get_the_title($project->ID)); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url($studio_projects_url); ?>" class="button"><?php echo esc_html__('All projects', 'binomio'); ?></a>
            </div>
            <div class="content-projects">
                <?php foreach (array_slice($studio_featured_projects, 0, 6) as $project) : ?>
                    <?php
                    $project_title = get_the_title($project->ID);
                    $project_permalink = $get_project_permalink($project->ID);
                    $project_featured_image = carbon_get_post_meta($project->ID, 'proyecto_featured_image');
                    $project_portada = carbon_get_post_meta($project->ID, 'proyecto_portada');
                    $project_image_id = !empty($project_featured_image) ? $project_featured_image : $project_portada;
                    $project_image_url = !empty($project_image_id) ? wp_get_attachment_url($project_image_id) : '';
                    $project_tags = carbon_get_post_meta($project->ID, 'proyecto_tags');
                    $project_subtitle = is_array($project_tags) ? implode(' · ', $project_tags) : '';
                    $project_description = tcf_meta($project->ID, 'proyecto_descripcion', 'proyecto_translations');
                    $project_description_text = wp_trim_words(wp_strip_all_tags((string) $project_description), 16, '...');
                    ?>
                    <div class="collection-card collection-card--displayed">
                        <a href="<?php echo esc_url($project_permalink); ?>">
                            <?php if (!empty($project_image_url)) : ?>
                                <img
                                    class="card-image"
                                    src="<?php echo esc_url($project_image_url); ?>"
                                    alt="<?php echo esc_attr($project_title); ?>">
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
                            <a href="<?php echo esc_url($project_permalink); ?>" class="button item-button"><?php echo esc_html__('See project', 'binomio'); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="about-card theme--artist">
                    <div class="card-image">
                    <img
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/thestudio.jpg"
                        alt="The Studio">
                    </div>
                    <div class="card-info">
                        <h3 class="item-title text-h1"><?php echo nl2br(esc_html__('The\nStudio', 'binomio')); ?></h3>
                        <a href="https://www.instagram.com/bnomio.studio" class="item-link">@bnomio.studio</a>
                        <a href="<?php echo esc_url($studio_about_url); ?>" class="button item-button"><?php echo esc_html__('About', 'binomio'); ?></a>
                    </div>
                </div>
            </div>
            <button class="exit-button button"><?php echo esc_html__('Go Back', 'binomio'); ?></button>
        </div>
        <div class="hero-footer">
            <div class="footer-main">
                <div class="footer-socials">
                    <a href="https://www.instagram.com/bnomio.studio" target="_blank" class="social-link">
                        <span class="icon icon-instagram"></span>
                        <p class="social-text">@BNOMIO.STUDIO</p>
                    </a>
                </div>
                <div class="footer-info">
                    <p><?php echo esc_html__('BNOMIO | COPYRIGHT 2025 ALL RIGHTS RESERVED', 'binomio'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <div class="container" style="display: none;">
    <h1 class="druk">Design system</h1>
    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Button</h2>
    <button class="button">Click Me</button>
    <button class="button button-icon">
        <span class="text">Click Me</span>
        <span class="icon icon-arrowright"></span>
    </button>
    <a class="button button-icon" href="#">
        <span class="icon icon-arrowright"></span>
    </a>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Links</h2>
    <a class="link" href="#">Link</a>
    <a class="link" href="#">Link</a>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Tabs</h2>
    <div class="tabs">
        <div class="tab selected" href="#" data-group="test">Tab 1</div>
        <div class="tab" href="#" data-group="test">Tab 2</div>
        <div class="tab" href="#" data-group="test">Tab 3</div>
    </div>

    <h2 class="druk" style="margin-top: 20px; margin-bottom: 10px">Tags</h2>
    <div class="tag-list">
        <div class="tag">Sculpture</div>
        <div class="tag">Illustration</div>
        <div class="tag">Hand-made</div>
        <div class="tag">Painting</div>
        <div class="tag">Photography</div>
        <div class="tag">Design</div>
        <div class="tag">Limited</div>
    </div>

    <h2 class="druk" style="margin-top: 40px; margin-bottom: 10px">Components</h2>
</div> -->
<?php
//get_template_part('components/templates/item-list');
?>
<?php
//get_template_part('components/templates/hero');
?>
<?php
//get_template_part('components/templates/collection-grid');
?>


<?php
get_template_part('nav', 'below');
get_footer();
?>