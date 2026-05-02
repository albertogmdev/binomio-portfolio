<?php
/**
 * Component Loader
 * 
 * Sistema de carga automática de componentes para Carbon Fields
 * Similar a ACF Flexible Content
 */

use TranslatableCarbonFields\Fields\Field;

class Binomio_Component_Loader {
    
    private static $components = array();
    
    /**
     * Inicializa el loader de componentes
     */
    public static function init() {
        add_action('carbon_fields_register_fields', array(__CLASS__, 'register_components'), 20);
    }
    
    /**
     * Escanea y registra todos los componentes disponibles
     */
    public static function register_components() {
        self::load_components();
        self::register_fields();
    }
    
    /**
     * Carga todos los componentes desde la carpeta components/fields
     */
    private static function load_components() {
        $components_dir = get_stylesheet_directory() . '/components/fields';
        
        if (!is_dir($components_dir)) {
            return;
        }
        
        $component_files = glob($components_dir . '/*.php');
        
        foreach ($component_files as $file) {
            $component_name = basename($file, '.php');
            $fields = include $file;
            
            if (is_array($fields)) {
                self::$components[$component_name] = array(
                    'name' => ucfirst(str_replace('_', ' ', $component_name)),
                    'fields' => $fields,
                );
            }
        }
    }
    
    /**
     * Registra los campos en Carbon Fields
     */
    private static function register_fields() {
        if (empty(self::$components)) {
            return;
        }
        
        $complex_field = Field::make('complex', 'crb_page_components', __('Componentes', 'binomio'))
            ->set_layout('tabbed-vertical');
        
        foreach (self::$components as $slug => $component) {
            $complex_field->add_fields($slug, $component['name'], $component['fields']);
        }
        
        // Registrar el campo solo en páginas
        \Carbon_Fields\Container\Container::make('post_meta', __('Constructor de Página', 'binomio'))
            ->where('post_type', '=', 'page')
            ->add_fields(Field::resolve(array(
                $complex_field
            )));
    }
    
    /**
     * Renderiza un componente específico
     * 
     * @param string $component_type Tipo de componente
     * @param array $data Datos del componente
     */
    public static function render_component($component_type, $data) {
        // Hardening: sólo permitir nombres con [a-z0-9_-] para prevenir LFI / path traversal
        if (!is_string($component_type) || !preg_match('/^[a-z0-9_-]+$/i', $component_type)) {
            echo '<!-- Componente inválido -->';
            return;
        }

        $templates_dir = realpath(get_stylesheet_directory() . '/components/templates');
        $template_path = realpath($templates_dir . '/' . $component_type . '.php');

        // Verificar que el path resuelto esté dentro del directorio de plantillas
        if (!$templates_dir || !$template_path || strpos($template_path, $templates_dir . DIRECTORY_SEPARATOR) !== 0) {
            echo '<!-- Componente ' . esc_html($component_type) . ' no encontrado -->';
            return;
        }

        if (!file_exists($template_path)) {
            echo '<!-- Componente ' . esc_html($component_type) . ' no encontrado -->';
            return;
        }

        $component = $data;
        include $template_path;
    }
    
    /**
     * Obtiene todos los componentes registrados
     * 
     * @return array
     */
    public static function get_components() {
        return self::$components;
    }
}

// Inicializar el loader
Binomio_Component_Loader::init();
