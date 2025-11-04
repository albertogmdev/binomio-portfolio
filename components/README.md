# Sistema de Componentes Binomio

Sistema modular de componentes para WordPress usando Carbon Fields, similar a ACF Flexible Content.

## 📁 Estructura

```
themes/binomio/
├── components/
│   ├── fields/          # Definición de campos (Carbon Fields)
│   │   └── hero.php     # Ejemplo: Componente Hero
│   └── templates/       # Templates de renderizado
│       ├── hero.php     # Template del Hero
│       └── hero.css     # Estilos del Hero
├── inc/
│   └── component-loader.php  # Sistema de auto-registro
└── template-section-based.php # Template de página
```

## 🚀 Cómo Usar

### 1. Crear un Nuevo Componente

Para añadir un nuevo componente (ejemplo: "cards"):

#### a) Crear el archivo de campos: `components/fields/cards.php`

```php
<?php
use Carbon_Fields\Field;

return array(
    Field::make('text', 'cards_title', __('Título', 'binomio')),
    Field::make('complex', 'cards_items', __('Tarjetas', 'binomio'))
        ->add_fields(array(
            Field::make('text', 'title', __('Título', 'binomio')),
            Field::make('textarea', 'description', __('Descripción', 'binomio')),
            Field::make('image', 'image', __('Imagen', 'binomio')),
        )),
);
```

#### b) Crear el template: `components/templates/cards.php`

```php
<?php
$title = $component['cards_title'] ?? '';
$items = $component['cards_items'] ?? array();
?>

<section class="component-cards">
    <?php if ($title): ?>
        <h2><?php echo esc_html($title); ?></h2>
    <?php endif; ?>
    
    <div class="cards-grid">
        <?php foreach ($items as $item): ?>
            <div class="card">
                <?php if (!empty($item['image'])): ?>
                    <img src="<?php echo esc_url($item['image']); ?>" alt="">
                <?php endif; ?>
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo esc_html($item['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
```

¡Eso es todo! El componente se registrará automáticamente.

### 2. Usar en una Página

1. Crea o edita una página en WordPress
2. Selecciona el template "Section-based"
3. En "Constructor de Página" añade componentes
4. Guarda y visualiza

## 🎨 Componentes Disponibles

### Hero
- Título, subtítulo, descripción
- Imagen de fondo
- Botón CTA
- Alineación (izquierda, centro, derecha)

## 📝 Campos de Carbon Fields Disponibles

```php
Field::make('text', ...)           // Texto simple
Field::make('textarea', ...)       // Área de texto
Field::make('rich_text', ...)      // Editor WYSIWYG
Field::make('image', ...)          // Imagen
Field::make('file', ...)           // Archivo
Field::make('select', ...)         // Select dropdown
Field::make('complex', ...)        // Campos repetibles
Field::make('association', ...)    // Relacionar posts
```

## 💡 Consejos

- El nombre del archivo en `fields/` debe coincidir con `templates/`
- Usa el prefijo del componente en los campos (ej: `hero_title`, `cards_title`)
- Los datos están en `$component` dentro de los templates
- El tipo de componente está en `$component['_type']`

## 🔧 Enqueue de Estilos

Añade esto a `functions.php` para cargar los CSS de componentes:

```php
add_action('wp_enqueue_scripts', 'binomio_enqueue_components_styles');
function binomio_enqueue_components_styles() {
    $components = array('hero', 'cards'); // Lista tus componentes
    foreach ($components as $component) {
        $css_path = get_template_directory() . '/components/templates/' . $component . '.css';
        if (file_exists($css_path)) {
            wp_enqueue_style(
                'component-' . $component,
                get_template_directory_uri() . '/components/templates/' . $component . '.css'
            );
        }
    }
}
```
