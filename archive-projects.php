<?php

/**
 * Archive template for Projects
 */

get_header();

$projects_post_type = post_type_exists('projects') ? 'projects' : 'proyectos';
$current_division = function_exists('is_studio') && is_studio() ? 'studio' : 'artist';

$projects_query = new WP_Query(array(
    'post_type' => $projects_post_type,
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'lang' => '',
    'orderby' => 'title',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'division',
            'field' => 'slug',
            'terms' => $current_division,
        ),
    ),
));

$projects = $projects_query->posts;

$format_tag_label = function ($tag_value) {
    if ($tag_value === 'ux_ui') {
        return 'UX/UI';
    }

    return ucwords(str_replace(array('_', '-'), ' ', (string) $tag_value));
};

$tag_values = array();
foreach ($projects as $project) {
    $project_tags = carbon_get_post_meta($project->ID, 'proyecto_tags');
    if (!is_array($project_tags)) {
        continue;
    }

    foreach ($project_tags as $tag_value) {
        $tag_value = trim((string) $tag_value);
        if ($tag_value === '') {
            continue;
        }

        $tag_values[$tag_value] = $tag_value;
    }
}

$tag_values = array_values($tag_values);
sort($tag_values);

if (empty($tag_values)) {
    $tag_values = array('all');
}
?>

<div class="archive-projects">
    <section class="section-hero">
        <div class="container">
            <div class="decoration-row">
                <span class="decoration decoration--topleft"></span>
                <span class="decoration decoration--topright"></span>
            </div>
            <div class="content-row">
                <div class="content-item content-topleft">
                    <h1 class="text-h1"><?php echo esc_html__('Works', 'binomio'); ?></h1>
                    <h2 class="text-h3"><?php echo esc_html__('Featured projects', 'binomio'); ?></h2>
                </div>
                <div class="content-item content-topright">
                    <p class="body-small"><?php echo esc_html__('A curated selection of featured projects.', 'binomio'); ?></p>
                </div>
            </div>
            <div class="decoration-row">
                <span class="decoration decoration--bottomleft"></span>
                <span class="decoration decoration--bottomright"></span>
            </div>
        </div>
    </section>
    <section class="section-collection_grid">
        <div class="container">
            <div class="tabs">
                <?php foreach ($tag_values as $index => $tag_value) : ?>
                    <?php
                    $panel_id = 'tag-' . sanitize_title($tag_value);
                    $tab_label = $tag_value === 'all' ? __('All', 'binomio') : $format_tag_label($tag_value);
                    ?>
                    <div class="tab <?php echo $index === 0 ? 'selected' : ''; ?>" data-panel="<?php echo esc_attr($panel_id); ?>" data-group="collection-list"><?php echo esc_html($tab_label); ?></div>
                <?php endforeach; ?>
            </div>

            <?php foreach ($tag_values as $index => $tag_value) : ?>
                <?php $panel_id = 'tag-' . sanitize_title($tag_value); ?>
                <div id="<?php echo esc_attr($panel_id); ?>" class="content-panel collection-list <?php echo $index === 0 ? '' : 'hidden-panel'; ?>">
                    <div class="collection-grid">
                        <?php foreach ($projects as $project_index => $project) : ?>
                            <?php
                            $project_tags = carbon_get_post_meta($project->ID, 'proyecto_tags');
                            $project_tags = is_array($project_tags) ? $project_tags : array();

                            if ($tag_value !== 'all' && !in_array($tag_value, $project_tags, true)) {
                                continue;
                            }

                            $project_title = get_the_title($project->ID);
                            $project_permalink = get_permalink($project->ID);

                            $project_featured_image = carbon_get_post_meta($project->ID, 'proyecto_featured_image');
                            $project_portada = carbon_get_post_meta($project->ID, 'proyecto_portada');
                            $project_image_id = !empty($project_featured_image) ? $project_featured_image : $project_portada;
                            $project_image_url = !empty($project_image_id) ? wp_get_attachment_url($project_image_id) : '';

                            $project_description = tcf_meta($project->ID, 'proyecto_descripcion', 'proyecto_translations');
                            $project_description_text = wp_trim_words(wp_strip_all_tags((string) $project_description), 16, '...');

                            $card_size_class = ($project_index % 4 === 2) ? 'item--twocol' : 'item--onecol';
                            ?>

                            <div class="collection-card item <?php echo esc_attr($card_size_class); ?>">
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
                                    <?php if (!empty($project_description_text)) : ?>
                                        <p class="item-description"><?php echo esc_html($project_description_text); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url($project_permalink); ?>" class="button item-button"><?php echo esc_html__('See project', 'binomio'); ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php
wp_reset_postdata();
get_footer();
