<?php

/**
 * Archive template for Cases
 */

get_header();

$current_division = function_exists('is_studio') && is_studio() ? 'studio' : 'artist';
$theme_class = $current_division === 'studio' ? 'theme--studio' : 'theme--artist';

$cases_query = new WP_Query(array(
    'post_type' => 'cases',
    'post_status' => 'publish',
    'posts_per_page' => -1,
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

$cases = $cases_query->posts;

if (empty($cases)) {
    $cases_query = new WP_Query(array(
        'post_type' => 'cases',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));

    $cases = $cases_query->posts;
}

$format_label = function ($value) {
    if ($value === 'ux_ui') {
        return 'UX/UI';
    }

    return ucwords(str_replace(array('_', '-'), ' ', (string) $value));
};

$tipo_values = array();
foreach ($cases as $case_post) {
    $case_tipo = carbon_get_post_meta($case_post->ID, 'case_tipo');
    if (!is_array($case_tipo)) {
        continue;
    }

    foreach ($case_tipo as $tipo) {
        $tipo = trim((string) $tipo);
        if ($tipo === '') {
            continue;
        }

        $tipo_values[$tipo] = $tipo;
    }
}

$tipo_values = array_values($tipo_values);
sort($tipo_values);

if (empty($tipo_values)) {
    $tipo_values = array('all');
}

$panels = array();
foreach ($tipo_values as $tipo) {
    $panel_id = 'cases-' . sanitize_title($tipo);
    $panel_cases = array();

    foreach ($cases as $case_post) {
        $case_tipos = carbon_get_post_meta($case_post->ID, 'case_tipo');
        $case_tipos = is_array($case_tipos) ? $case_tipos : array();

        if ($tipo !== 'all' && !in_array($tipo, $case_tipos, true)) {
            continue;
        }

        $case_id = $case_post->ID;
        $case_title = get_the_title($case_id);
        $case_subtitle = tcf_meta($case_id, 'case_subtitulo', 'case_translations');
        $case_content = tcf_meta($case_id, 'case_contenido', 'case_translations');
        $case_category = (string) carbon_get_post_meta($case_id, 'case_categoria');
        $case_year = (string) carbon_get_post_meta($case_id, 'case_ano');
        $case_image_id = carbon_get_post_meta($case_id, 'case_imagen');
        $case_image_url = !empty($case_image_id) ? wp_get_attachment_url($case_image_id) : '';
        $case_links = carbon_get_post_meta($case_id, 'case_links');

        $normalized_links = array();
        if (is_array($case_links)) {
            foreach ($case_links as $case_link) {
                $link_text = trim(tcf_item($case_link, 'texto'));
                $link_url = trim(tcf_url($case_link['url'] ?? ''));

                if ($link_url === '') {
                    continue;
                }

                $normalized_links[] = array(
                    'text' => $link_text !== '' ? $link_text : __('Ver más', 'binomio'),
                    'url' => $link_url,
                );
            }
        }

        $panel_cases[] = array(
            'id' => $case_id,
            'title' => $case_title,
            'subtitle' => $case_subtitle,
            'content' => wp_kses_post($case_content),
            'category' => $case_category !== '' ? $format_label($case_category) : '',
            'year' => $case_year,
            'image' => $case_image_url,
            'links' => $normalized_links,
        );
    }

    $panels[] = array(
        'id' => $panel_id,
        'tipo' => $tipo,
        'label' => $tipo === 'all' ? __('All', 'binomio') : $format_label($tipo),
        'cases' => $panel_cases,
    );
}

$modal_cases_data = array();
foreach ($panels as $panel) {
    $modal_cases_data[$panel['id']] = $panel['cases'];
}
?>

<section class="archive-cases section-item_list">
    <section class="section-hero <?php echo esc_attr($theme_class); ?>">
        <div class="container">
            <div class="decoration-row">
                <span class="decoration decoration--topleft"></span>
                <span class="decoration decoration--topright"></span>
            </div>
            <div class="content-row">
                <div class="content-item content-topleft">
                    <h1 class="text-h1"><?php echo esc_html(bnm_t('archive_cases_title', 'Archive')); ?></h1>
                    <h2 class="text-h3"><?php echo esc_html(bnm_t('archive_cases_subtitle', 'A selection of past works')); ?></h2>
                </div>
                <div class="content-item content-topright">
                    <p class="body-small"><?php echo esc_html($current_division === 'studio' ? bnm_t('archive_cases_studio', 'Studio archive cases') : bnm_t('archive_cases_artist', 'Artist archive cases')); ?></p>
                </div>
            </div>
            <div class="decoration-row">
                <span class="decoration decoration--bottomleft"></span>
                <span class="decoration decoration--bottomright"></span>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="tabs">
                <?php foreach ($panels as $index => $panel) : ?>
                    <div class="tab <?php echo $index === 0 ? 'selected' : ''; ?>" data-panel="<?php echo esc_attr($panel['id']); ?>" data-group="cases-list"><?php echo esc_html($panel['label']); ?></div>
                <?php endforeach; ?>
            </div>

            <?php foreach ($panels as $index => $panel) : ?>
                <div id="<?php echo esc_attr($panel['id']); ?>" class="content-panel cases-list <?php echo $index === 0 ? '' : 'hidden-panel'; ?>">
                    <?php if (!empty($panel['cases'])) : ?>
                        <ul class="item-list">
                            <?php foreach ($panel['cases'] as $case_index => $case_item) : ?>
                                <li>
                                    <button class="link-info" data-panel="<?php echo esc_attr($panel['id']); ?>" data-case-index="<?php echo esc_attr($case_index); ?>">
                                        <div class="info-item info-item--first"><?php echo esc_html($case_item['title']); ?></div>
                                        <div class="info-item info-item--second"><?php echo esc_html($case_item['category']); ?></div>
                                        <div class="info-item info-item--third"><?php echo esc_html($case_item['year']); ?></div>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="body-small"><?php echo esc_html(bnm_t('archive_cases_empty', 'No hay cases para mostrar.')); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div id="archive-modal" class="modal modal-archive">
        <div class="modal-main">
            <button class="button button-icon modal-close">
                <span class="icon icon-close"></span>
            </button>
            <div class="modal-body <?php echo esc_attr($theme_class); ?>">
                <div class="modal-image">
                    <div class="decoration-row">
                        <span class="decoration decoration--topleft"></span>
                        <span class="decoration decoration--topright"></span>
                    </div>
                    <img src="" alt="" class="archive-modal-image" />
                    <div class="decoration-row">
                        <span class="decoration decoration--bottomleft"></span>
                        <span class="decoration decoration--bottomright"></span>
                    </div>
                </div>
                <div class="modal-content">
                    <p class="modal-title text-h2"></p>
                    <p class="modal-subtitle text-h4"></p>
                    <div class="modal-description body-small"></div>
                    <div class="modal-buttons"></div>
                </div>
            </div>
            <div class="modal-pagination <?php echo esc_attr($theme_class); ?>">
                <button class="pagination-item modal-prev">
                    <span class="icon icon-chevronleft"></span>
                    <span class="text"><?php echo esc_html(bnm_t('archive_prev', 'Prev')); ?></span>
                </button>
                <button class="pagination-item modal-next">
                    <span class="text"><?php echo esc_html(bnm_t('archive_next', 'Next')); ?></span>
                    <span class="icon icon-chevronright"></span>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    window.BINOMIO_CASES_MODAL_DATA = <?php echo wp_json_encode($modal_cases_data); ?>;
</script>

<?php
get_footer();
