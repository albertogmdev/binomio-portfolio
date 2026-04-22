<?php
/**
 * Theme Strings Manager
 *
 * Provides an admin UI (Carbon Fields options page) to edit all
 * frontend-facing UI strings for every active Polylang language.
 *
 * Usage in templates:
 *   bnm_t('key', 'fallback text')
 *   bnm_t('key', 'fallback', 'fr')  // force language
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field;

// ---------------------------------------------------------------------------
// Helper: retrieve a string for the current (or specified) language
// ---------------------------------------------------------------------------
function bnm_t(string $key, string $fallback = '', string $lang = ''): string {
    if ($lang === '') {
        $lang = function_exists('tcf_get_current_language') ? tcf_get_current_language() : (
            function_exists('pll_default_language') ? pll_default_language() : 'es'
        );
    }
    $value = carbon_get_theme_option('bnm_str_' . $key . '_' . $lang);
    if ($value !== '' && $value !== null) {
        return $value;
    }
    // Fall back to the site default language
    $default_lang = function_exists('pll_default_language') ? pll_default_language() : 'es';
    if ($lang !== $default_lang) {
        $value_def = carbon_get_theme_option('bnm_str_' . $key . '_' . $default_lang);
        if ($value_def !== '' && $value_def !== null) {
            return $value_def;
        }
    }
    return $fallback;
}

// ---------------------------------------------------------------------------
// Carbon Fields options page
// ---------------------------------------------------------------------------
add_action('carbon_fields_register_fields', function () {

    // Detect active languages from Polylang; fall back to es+en
    $langs = function_exists('pll_languages_list')
        ? pll_languages_list(array('fields' => 'slug'))
        : array('es', 'en');

    if (empty($langs)) {
        $langs = array('es', 'en');
    }

    $lang_count = count($langs);
    $width      = max(25, (int) floor(100 / $lang_count));

    /**
     * Generate one text field per active language for a given string key.
     *
     * @param string $key      Option key suffix (e.g. 'nav_collections')
     * @param string $label    Human-readable label (without lang suffix)
     * @param array  $defaults Map of lang => default value. Missing langs use ''
     */
    $pair = function (string $key, string $label, array $defaults = []) use ($langs, $width): array {
        $fields = [];
        foreach ($langs as $lang) {
            $default = $defaults[$lang] ?? ($defaults['*'] ?? '');
            $fields[] = Field::make('text', 'bnm_str_' . $key . '_' . $lang, $label . ' (' . strtoupper($lang) . ')')
                ->set_default_value($default)
                ->set_width($width);
        }
        return $fields;
    };

    $fields = [];

    // ---- Navigation -------------------------------------------------------
    $fields[] = Field::make('separator', 'sep_nav', __('Navegación', 'binomio'));
    $fields = array_merge($fields, $pair('nav_collections', 'Collections',  ['es' => 'Collections',  'en' => 'Collections']));
    $fields = array_merge($fields, $pair('nav_archive',     'Archive',      ['es' => 'Archive',      'en' => 'Archive']));
    $fields = array_merge($fields, $pair('nav_about',       'About',        ['es' => 'About',        'en' => 'About']));
    $fields = array_merge($fields, $pair('nav_contact',     'Contact',      ['es' => 'Contacto',     'en' => 'Contact']));
    $fields = array_merge($fields, $pair('nav_studio_zone', 'Studio zone',  ['es' => 'Studio zone',  'en' => 'Studio zone']));
    $fields = array_merge($fields, $pair('nav_artist_zone', 'Artist zone',  ['es' => 'Artist zone',  'en' => 'Artist zone']));

    // ---- Home – Artist zone -----------------------------------------------
    $fields[] = Field::make('separator', 'sep_home_artist', __('Home — Zona artista', 'binomio'));
    $fields = array_merge($fields, $pair('home_artist_intro',    'Intro text (artist)',       ['es' => 'Creative Consultant', 'en' => 'Creative Consultant']));
    $fields = array_merge($fields, $pair('home_artist_enter',    'Botón entrar (artist)',     ['es' => 'Coming soon',         'en' => 'Coming soon']));
    $fields = array_merge($fields, $pair('home_artist_projects', 'Título recientes',          ['es' => 'Recent Projects',     'en' => 'Recent Projects']));
    $fields = array_merge($fields, $pair('home_artist_all',      'Botón todos los proyectos', ['es' => 'All projects',        'en' => 'All projects']));
    $fields = array_merge($fields, $pair('home_see_project',     'Botón ver proyecto',        ['es' => 'Ver proyecto',        'en' => 'See project']));
    $fields = array_merge($fields, $pair('home_go_back',         'Botón go back',             ['es' => 'Volver',              'en' => 'Go Back']));

    // ---- Home – Studio zone -----------------------------------------------
    $fields[] = Field::make('separator', 'sep_home_studio', __('Home — Zona studio', 'binomio'));
    $fields = array_merge($fields, $pair('home_studio_intro',    'Intro text (studio)',       ['es' => 'Nomad Design Studio', 'en' => 'Nomad Design Studio']));
    $fields = array_merge($fields, $pair('home_studio_enter',    'Botón entrar (studio)',     ['es' => 'Entrar',              'en' => 'Enter']));
    $fields = array_merge($fields, $pair('home_studio_projects', 'Título recientes (studio)', ['es' => 'Recent Projects',     'en' => 'Recent Projects']));
    $fields = array_merge($fields, $pair('home_studio_all',      'Botón todos (studio)',      ['es' => 'All projects',        'en' => 'All projects']));
    $fields = array_merge($fields, $pair('home_studio_name',     'Nombre studio (card)',      ['*'  => "The\nStudio"]));
    $fields = array_merge($fields, $pair('home_studio_about',    'Botón about (studio card)', ['es' => 'Sobre nosotros',      'en' => 'About']));

    // ---- Archive – Projects -----------------------------------------------
    $fields[] = Field::make('separator', 'sep_archive_projects', __('Archivo — Proyectos', 'binomio'));
    $fields = array_merge($fields, $pair('archive_works_title',    'Título Works',     ['es' => 'Works',     'en' => 'Works']));
    $fields = array_merge($fields, $pair('archive_works_subtitle', 'Subtítulo Works',  ['es' => 'Proyectos destacados',                    'en' => 'Featured projects']));
    $fields = array_merge($fields, $pair('archive_works_desc',     'Descripción Works',['es' => 'Una selección curada de proyectos destacados.', 'en' => 'A curated selection of featured projects.']));
    $fields = array_merge($fields, $pair('archive_works_all_tab',  'Tab "All"',        ['es' => 'Todos',   'en' => 'All']));

    // ---- Archive – Cases --------------------------------------------------
    $fields[] = Field::make('separator', 'sep_archive_cases', __('Archivo — Cases', 'binomio'));
    $fields = array_merge($fields, $pair('archive_cases_title',    'Título Archive',    ['es' => 'Archivo',  'en' => 'Archive']));
    $fields = array_merge($fields, $pair('archive_cases_subtitle', 'Subtítulo Archive', ['es' => 'Una selección de trabajos pasados', 'en' => 'A selection of past works']));
    $fields = array_merge($fields, $pair('archive_cases_studio',   'Descripción studio',['es' => 'Cases del estudio',  'en' => 'Studio archive cases']));
    $fields = array_merge($fields, $pair('archive_cases_artist',   'Descripción artista',['es' => 'Cases del artista', 'en' => 'Artist archive cases']));
    $fields = array_merge($fields, $pair('archive_cases_empty',    'Sin cases',         ['es' => 'No hay cases para mostrar.', 'en' => 'No cases to display.']));
    $fields = array_merge($fields, $pair('archive_cases_all_tab',  'Tab "All"',         ['es' => 'Todos', 'en' => 'All']));
    $fields = array_merge($fields, $pair('archive_prev',           'Prev',              ['es' => 'Anterior', 'en' => 'Prev']));
    $fields = array_merge($fields, $pair('archive_next',           'Next',              ['es' => 'Siguiente','en' => 'Next']));

    // ---- Single project ---------------------------------------------------
    $fields[] = Field::make('separator', 'sep_single', __('Single — Proyecto', 'binomio'));
    $fields = array_merge($fields, $pair('single_credits', 'Sección Credits',       ['es' => 'CRÉDITOS',       'en' => 'CREDITS']));
    $fields = array_merge($fields, $pair('single_other',   'Sección Other Projects',['es' => 'OTROS PROYECTOS','en' => 'OTHER PROJECTS']));

    // ---- 404 --------------------------------------------------------------
    $fields[] = Field::make('separator', 'sep_404', __('Página 404', 'binomio'));
    $fields = array_merge($fields, $pair('404_subtitle', 'Subtítulo',   ['es' => 'Página no encontrada',                                            'en' => 'Page not found']));
    $fields = array_merge($fields, $pair('404_desc',     'Descripción', ['es' => 'La página que buscas no existe o ha sido movida.',                'en' => 'The page you are looking for does not exist or has been moved.']));
    $fields = array_merge($fields, $pair('404_cta',      'Botón volver',['es' => 'Volver al inicio',                                               'en' => 'Back to home']));

    // ---- Footer -----------------------------------------------------------
    $fields[] = Field::make('separator', 'sep_footer', __('Footer', 'binomio'));
    $fields = array_merge($fields, $pair('footer_copyright', 'Copyright', ['*' => 'BNOMIO | COPYRIGHT 2025 ALL RIGHTS RESERVED']));

    // ---- Forms (frontend) -------------------------------------------------
    $fields[] = Field::make('separator', 'sep_forms', __('Forms (frontend)', 'binomio'));
    $fields = array_merge($fields, $pair('form_submit',         'Botón enviar',        ['es' => 'Enviar',                            'en' => 'Send']));
    $fields = array_merge($fields, $pair('form_select_default', 'Opción vacía select', ['es' => '— Seleccionar —',                   'en' => '— Select —']));
    $fields = array_merge($fields, $pair('form_success',        'Mensaje éxito',       ['es' => '¡Gracias! Nos pondremos en contacto contigo en breve.', 'en' => 'Thank you! We will get back to you shortly.']));
    $fields = array_merge($fields, $pair('form_error_generic',  'Error genérico',      ['es' => 'Ha ocurrido un error, inténtalo de nuevo.', 'en' => 'An error occurred. Please try again.']));

    Container::make('theme_options', __('Textos UI', 'binomio'))
        ->set_page_menu_title(__('Textos UI', 'binomio'))
        ->set_icon('dashicons-translation')
        ->set_page_position(27)
        ->add_fields($fields);
});

// Ensure Cases CPT appears at 26 so Textos UI lands right below it
add_action('admin_menu', function () {
    global $menu;
    // Avoid position collision at 27 (CF may already handle it via set_page_position)
    ksort($menu);
}, 999);
