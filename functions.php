<?php
/**
 * Binomio Theme Functions
 */

use Carbon_Fields\Container;

add_action('after_setup_theme', 'binomio_theme_setup');
function binomio_theme_setup() {
    load_theme_textdomain('binomio', get_stylesheet_directory() . '/languages');
}

// Cargar Carbon Fields
add_action('after_setup_theme', 'binomio_load_carbon_fields');
function binomio_load_carbon_fields() {
    \Carbon_Fields\Carbon_Fields::boot();
}

// Cargar el sistema de componentes
if (!class_exists('TranslatableCarbonFields\\Fields\\Field')) {
    class TCF_Field_Fallback {
        public static function make($type, $key, $label) {
            return \Carbon_Fields\Field::make($type, $key, $label);
        }

        public static function resolve($items) {
            return (array) $items;
        }
    }

    class_alias('TCF_Field_Fallback', 'TranslatableCarbonFields\\Fields\\Field');
}

// Fallbacks para Translatable_Field y Translatable cuando el plugin translatable-carbon-fields está inactivo
if (!class_exists('Translatable_Field')) {
    class Translatable_Field {
        private $type, $key, $label;

        private function __construct($type, $key, $label) {
            $this->type  = $type;
            $this->key   = $key;
            $this->label = $label;
        }

        public static function make($type, $key, $label) {
            return new self($type, $key, $label);
        }

        public function base_field() {
            return \Carbon_Fields\Field::make($this->type, $this->key, $this->label);
        }
    }
}

if (!class_exists('Translatable')) {
    class Translatable {
        public static function fields($items, $config = array()) {
            $fields = array();
            foreach ((array) $items as $item) {
                if ($item instanceof Translatable_Field) {
                    $fields[] = $item->base_field();
                } elseif ($item instanceof \Carbon_Fields\Field\Field) {
                    $fields[] = $item;
                }
            }
            return $fields;
        }
    }
}

require_once get_stylesheet_directory() . '/inc/component-loader.php';
require_once get_stylesheet_directory() . '/inc/post-type-proyectos.php';
require_once get_stylesheet_directory() . '/inc/post-type-cases.php';
require_once get_stylesheet_directory() . '/inc/post-type-stickers.php';
require_once get_stylesheet_directory() . '/inc/forms-manager.php';
require_once get_stylesheet_directory() . '/inc/theme-strings.php';

// Ocultar el editor de contenido en páginas (solo usar constructor de componentes)
add_action('admin_init', 'binomio_hide_editor_on_pages');
function binomio_hide_editor_on_pages() {
    // Ocultar el editor en todas las páginas
    remove_post_type_support('page', 'editor');
}

// Permitir subida de archivos SVG
add_filter('upload_mimes', 'binomio_allow_svg_upload');
function binomio_allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

// Validar archivos SVG al subir
add_filter('wp_check_filetype_and_ext', 'binomio_check_svg_filetype', 10, 4);
function binomio_check_svg_filetype($data, $file, $filename, $mimes) {
    if (strlen($filename) > 4 && strtolower(substr($filename, -4)) === '.svg') {
        $data['type'] = 'image/svg+xml';
        $data['ext'] = 'svg';
    }
    return $data;
}

// Fallbacks de traducción — solo activos si el plugin está desactivado
if (!function_exists('tcf_component')) {
    function tcf_component($component, $key, $default = '') {
        return isset($component[$key]) ? (string) $component[$key] : (string) $default;
    }
}

if (!function_exists('tcf_item')) {
    function tcf_item($item, $key, $default = '') {
        return isset($item[$key]) ? (string) $item[$key] : (string) $default;
    }
}

if (!function_exists('tcf_url')) {
    function tcf_url($url) {
        return (string) $url;
    }
}

if (!function_exists('tcf_meta')) {
    function tcf_meta($post_id, $key, $translations_key = 'content_translations') {
        return (string) carbon_get_post_meta($post_id, $key);
    }
}

// Cargar estilos
add_action('wp_enqueue_scripts', 'binomio_enqueue_components_styles');
function binomio_enqueue_components_styles() {
    // Cargar CSS principal
    wp_enqueue_style('binomio-css', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '1.0.0');

    // Cargar jQuery (incluido en WordPress)
    wp_enqueue_script('jquery');

    // Cargar main.js
    wp_enqueue_script('binomio-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
}

if (!function_exists('tcf_get_current_language')) {
    function tcf_get_current_language() {
        // The URL prefix is the source of truth for CPTs that use a single post
        // with Carbon Fields translations (no Polylang duplicates), because
        // pll_current_language() may return false for custom rewrite rules that
        // don't include a lang query var, falling through to determine_locale()
        // and returning the default language even on /en/ URLs.
        $uri = isset($_SERVER['REQUEST_URI']) ? strtok((string) $_SERVER['REQUEST_URI'], '?') : '';
        if ($uri !== '' && function_exists('pll_languages_list')) {
            $registered_langs = pll_languages_list();
            if (is_array($registered_langs)) {
                foreach ($registered_langs as $lang_slug) {
                    if (strpos($uri, '/' . $lang_slug . '/') === 0 || $uri === '/' . $lang_slug) {
                        return $lang_slug;
                    }
                }
            }
        }

        if (function_exists('pll_current_language')) {
            $language = pll_current_language('slug');

            if (is_string($language) && $language !== '') {
                return $language;
            }
        }

        $locale = determine_locale();

        if (!is_string($locale) || $locale === '') {
            return 'es';
        }

        $locale_parts = explode('_', $locale);

        return strtolower((string) $locale_parts[0]);
    }
}

if (!function_exists('binomio_get_current_language')) {
    function binomio_get_current_language() {
        return tcf_get_current_language();
    }
}

if (!function_exists('binomio_normalize_path_candidates')) {
    function binomio_normalize_path_candidates($paths) {
        if (!is_array($paths)) {
            $paths = array($paths);
        }

        $normalized_paths = array();

        foreach ($paths as $path) {
            $path = trim((string) $path);

            if ($path === '') {
                continue;
            }

            $normalized_paths[] = trim($path, '/');
        }

        return array_values(array_unique($normalized_paths));
    }
}

if (!function_exists('binomio_get_localized_page_url')) {
    function binomio_get_localized_page_url($localized_paths, $fallback_path = '/') {
        $current_language = binomio_get_current_language();
        $candidate_paths = array();

        if (isset($localized_paths[$current_language])) {
            $candidate_paths = array_merge($candidate_paths, binomio_normalize_path_candidates($localized_paths[$current_language]));
        }

        foreach ((array) $localized_paths as $language => $paths) {
            if ($language === $current_language) {
                continue;
            }

            $candidate_paths = array_merge($candidate_paths, binomio_normalize_path_candidates($paths));
        }

        $candidate_paths = array_values(array_unique($candidate_paths));

        foreach ($candidate_paths as $candidate_path) {
            $page = get_page_by_path($candidate_path);

            if (!$page instanceof WP_Post) {
                continue;
            }

            if (function_exists('pll_get_post')) {
                $translated_page_id = pll_get_post($page->ID, $current_language);

                if (!empty($translated_page_id)) {
                    return get_permalink($translated_page_id);
                }
            }

            return get_permalink($page->ID);
        }

        $fallback_path = trim((string) $fallback_path);

        if ($fallback_path === '') {
            return home_url('/');
        }

        return home_url(user_trailingslashit(ltrim($fallback_path, '/')));
    }
}

if (!function_exists('binomio_get_language_switcher_items')) {
    function binomio_get_language_switcher_items() {
        if (!function_exists('pll_the_languages')) {
            return array(
                array(
                    'label' => strtoupper(binomio_get_current_language()),
                    'url' => '',
                    'current' => true,
                ),
            );
        }

        $languages = pll_the_languages(array(
            'raw' => 1,
            'hide_if_empty' => 0,
            'hide_if_no_translation' => 0,
        ));

        if (!is_array($languages) || empty($languages)) {
            return array(
                array(
                    'label' => strtoupper(binomio_get_current_language()),
                    'url' => '',
                    'current' => true,
                ),
            );
        }

        // For CPTs without Polylang duplicates (projects, cases) and division archives,
        // Polylang returns the language home URL instead of the current page URL.
        // In those cases, build the URL by swapping the language prefix in the current URI.
        $needs_prefix_swap = is_singular(array('projects', 'cases'))
            || is_post_type_archive(array('projects', 'cases'))
            || get_query_var('division') !== '';

        $current_lang    = binomio_get_current_language();
        $current_uri     = isset($_SERVER['REQUEST_URI']) ? strtok((string) $_SERVER['REQUEST_URI'], '?') : '/';
        $default_lang    = function_exists('pll_default_language') ? (string) pll_default_language('slug') : '';

        $items = array();

        foreach ($languages as $language) {
            $slug = isset($language['slug']) ? (string) $language['slug'] : '';

            if ($slug === '') {
                continue;
            }

            if ($needs_prefix_swap) {
                // Strip current language prefix from URI (handles both prefixed and prefix-less default lang).
                $path = $current_uri;

                if ($current_lang !== '' && strpos($path, '/' . $current_lang . '/') === 0) {
                    $path = substr($path, strlen('/' . $current_lang));
                } elseif ($path === '/' . $current_lang || $path === '/' . $current_lang . '/') {
                    $path = '/';
                }

                // If the target language is the default and Polylang serves it without prefix, omit prefix.
                if ($slug === $default_lang && $default_lang !== '') {
                    $url = home_url($path);
                } else {
                    $url = home_url('/' . $slug . $path);
                }
            } else {
                $url = isset($language['url']) ? (string) $language['url'] : '';
            }

            $items[] = array(
                'label'   => strtoupper($slug),
                'url'     => $url,
                'current' => !empty($language['current_lang']),
            );
        }

        return !empty($items) ? $items : array(
            array(
                'label' => strtoupper($current_lang),
                'url' => '',
                'current' => true,
            ),
        );
    }
}

if (!function_exists('tcf_get_language_slugs')) {
    function tcf_get_language_slugs() {
        if (function_exists('pll_languages_list')) {
            $languages = pll_languages_list();

            if (is_array($languages) && !empty($languages)) {
                return array_values(array_filter(array_map('strval', $languages)));
            }
        }

        return array();
    }
}

if (!function_exists('binomio_get_language_slugs')) {
    function binomio_get_language_slugs() {
        return tcf_get_language_slugs();
    }
}

if (!function_exists('binomio_get_request_path')) {
    function binomio_get_request_path() {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $path = trim((string) parse_url($request_uri, PHP_URL_PATH), '/');
        $language_slugs = binomio_get_language_slugs();

        if ($path === '' || empty($language_slugs)) {
            return $path;
        }

        foreach ($language_slugs as $language_slug) {
            $language_slug = trim((string) $language_slug, '/');

            if ($language_slug === '') {
                continue;
            }

            if ($path === $language_slug) {
                return '';
            }

            if (strpos($path, $language_slug . '/') === 0) {
                return substr($path, strlen($language_slug) + 1);
            }
        }

        return $path;
    }
}

if (!function_exists('binomio_get_route_slug')) {
    function binomio_get_route_slug($route, $language = null) {
        if (!is_string($language) || $language === '') {
            $language = binomio_get_current_language();
        }

        $route_map = array(
            'studio' => array(
                'default' => 'studio',
            ),
            'projects' => array(
                'default' => 'projects',
            ),
            'cases' => array(
                'default' => 'cases',
            ),
        );

        if (!isset($route_map[$route])) {
            return trim((string) $route, '/');
        }

        if (isset($route_map[$route][$language])) {
            return $route_map[$route][$language];
        }

        return $route_map[$route]['default'];
    }
}

if (!function_exists('binomio_get_projects_archive_path')) {
    function binomio_get_projects_archive_path($division = 'artist', $language = null) {
        $projects_slug = binomio_get_route_slug('projects', $language);

        if ($division === 'studio') {
            return trim(binomio_get_route_slug('studio', $language) . '/' . $projects_slug, '/');
        }

        return trim($projects_slug, '/');
    }
}

if (!function_exists('binomio_get_projects_archive_url')) {
    function binomio_get_projects_archive_url($division = 'artist', $language = null) {
        return home_url(user_trailingslashit(binomio_get_projects_archive_path($division, $language)));
    }
}

if (!function_exists('binomio_path_matches')) {
    function binomio_path_matches($path, $candidates) {
        $path = trim((string) $path, '/');

        foreach ((array) $candidates as $candidate) {
            if ($path === trim((string) $candidate, '/')) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('binomio_add_localized_rewrite_rule')) {
    function binomio_add_localized_rewrite_rule($path, $query, $after = 'top') {
        $path = trim((string) $path, '/');

        if ($path === '') {
            return;
        }

        add_rewrite_rule('^' . $path, $query, $after);

        foreach (binomio_get_language_slugs() as $language_slug) {
            $language_slug = trim((string) $language_slug, '/');

            if ($language_slug === '') {
                continue;
            }

            add_rewrite_rule('^' . preg_quote($language_slug, '#') . '/' . $path, $query, $after);
        }
    }
}

if (!function_exists('binomio_get_post_language')) {
    function binomio_get_post_language($post_id) {
        $post_id = (int) $post_id;

        if ($post_id <= 0) {
            return binomio_get_current_language();
        }

        if (function_exists('pll_get_post_language')) {
            $language = pll_get_post_language($post_id, 'slug');

            if (is_string($language) && $language !== '') {
                return $language;
            }
        }

        return binomio_get_current_language();
    }
}

if (!function_exists('binomio_get_translated_post_id')) {
    function binomio_get_translated_post_id($post_id, $language = null) {
        $post_id = (int) $post_id;

        if ($post_id <= 0) {
            return 0;
        }

        if (!is_string($language) || $language === '') {
            $language = binomio_get_current_language();
        }

        if (function_exists('pll_get_post')) {
            $translated_post_id = pll_get_post($post_id, $language);

            if (!empty($translated_post_id)) {
                return (int) $translated_post_id;
            }
        }

        return $post_id;
    }
}

if (!function_exists('is_studio')) {
    function is_studio() {
        if (is_front_page() || is_home()) {
            return false;
        }

        if (get_query_var('division') === 'studio') {
            return true;
        }

        return is_page('studio');
    }
}

if (!function_exists('is_artist')) {
    function is_artist() {
        if (is_front_page() || is_home()) {
            return false;
        }

        if (get_query_var('division') === 'artist') {
            return true;
        }

        return !is_studio();
    }
}

// Prevent Polylang from redirecting project/case posts and archives to their assigned
// language. These CPTs use a single post per item (stored in 'es') with Carbon Fields
// translations, so they must be accessible under any language URL prefix (or none) without
// a canonical redirect.
add_filter('pll_check_canonical_url', function ($redirect_url, $language) {
    // Suppress redirect for CPT archives (e.g. /studio/projects/ without language prefix).
    $multilingual_cpts = array('projects', 'proyectos', 'cases');
    foreach ($multilingual_cpts as $cpt) {
        if (is_post_type_archive($cpt)) {
            return false;
        }
    }

    if (!is_singular()) {
        return $redirect_url;
    }

    $post = get_queried_object();
    if (!$post instanceof WP_Post) {
        return $redirect_url;
    }

    // CPTs that exist only in the default language (no Polylang duplicates)
    if (in_array($post->post_type, $multilingual_cpts, true)) {
        return false;
    }

    // Pages: if there is no translated version for the requested language,
    // let page.php handle the fallback instead of redirecting to the default language.
    if ($post->post_type === 'page' && function_exists('pll_get_post') && function_exists('pll_default_language')) {
        $requested_lang = pll_get_post_language($post->ID, 'slug');
        $default_lang   = pll_default_language('slug');
        // If we are on the default-language post but the URL has a secondary lang
        // prefix it means Polylang couldn't find the translation — suppress redirect.
        if ($requested_lang === $default_lang) {
            $uri = isset($_SERVER['REQUEST_URI']) ? strtok((string) $_SERVER['REQUEST_URI'], '?') : '';
            if (function_exists('pll_languages_list')) {
                foreach (pll_languages_list() as $lang_slug) {
                    if ($lang_slug !== $default_lang && strpos($uri, '/' . $lang_slug . '/') === 0) {
                        return false;
                    }
                }
            }
        }
    }

    return $redirect_url;
}, 10, 2);

// Prevent Polylang from rewriting our custom CPT archive rules.
// binomio_add_localized_rewrite_rule already registers all language-prefixed variants
// (no prefix, /es/, /en/). If Polylang also processes them it drops the no-prefix rule
// when "force language in URL for the default language" is active.
add_filter('pll_modify_rewrite_rule', function ($modify, $rule_array, $filter, $cpt_archive) {
    $rule_value = reset($rule_array);

    if (
        is_string($rule_value)
        && preg_match('#post_type=(projects|cases)#', $rule_value)
        && strpos($rule_value, 'name=') === false
    ) {
        return false; // Leave the rule untouched; Polylang will keep the original.
    }

    return $modify;
}, 10, 4);