# 🎨 Sistema de Componentes Binomio - Guía Completa

## 📋 Resumen

Has implementado un sistema de componentes modulares similar a **ACF Flexible Content** pero usando **Carbon Fields**. Este sistema te permite:

- ✅ Crear componentes reutilizables fácilmente
- ✅ Auto-registro de componentes (solo creas los archivos)
- ✅ Separación clara entre campos y presentación
- ✅ Sistema similar a ACF que ya conoces

## 🗂️ Estructura de Archivos

```
wp-content/themes/binomio/
│
├── components/
│   ├── fields/              # 📝 Definición de campos (backend)
│   │   ├── hero.php         # Campos del componente Hero
│   │   └── content.php      # Campos del componente Content
│   │
│   ├── templates/           # 🎨 Templates de renderizado (frontend)
│   │   ├── hero.php         # HTML del Hero
│   │   ├── hero.css         # Estilos del Hero
│   │   ├── content.php      # HTML del Content
│   │   └── content.css      # Estilos del Content
│   │
│   └── README.md            # Documentación básica
│
├── inc/
│   └── component-loader.php # 🔧 Motor del sistema (no tocar)
│
├── functions.php            # Configuración del tema
└── template-section-based.php # Template de página
```

## 🚀 Cómo Usar

### Paso 1: Crea una página con componentes

1. En WordPress, ve a **Páginas → Añadir nueva**
2. Dale un título a la página
3. En **Atributos de página → Plantilla**, selecciona **"Section-based"**
4. Verás un nuevo metabox: **"Constructor de Página"**
5. Haz clic en **"Add Components"** o similar
6. Selecciona el componente que quieres añadir (Hero, Content, etc.)
7. Rellena los campos
8. Publica o actualiza la página

### Paso 2: Visualiza la página

Los componentes se renderizarán automáticamente en el orden que los hayas añadido.

## 🔨 Componentes Disponibles

### 1. **Hero** - Cabecera principal

**Campos:**
- Título
- Subtítulo
- Descripción
- Imagen de fondo
- Alineación (izquierda, centro, derecha)
- Texto del botón
- Enlace del botón

**Uso:** Perfecto para headers, landing pages, secciones destacadas.

### 2. **Content** - Bloque de contenido

**Campos:**
- Título
- Contenido (WYSIWYG)
- Ancho del contenedor (estrecho, medio, ancho)
- Color de fondo (blanco, gris, oscuro)

**Uso:** Secciones de texto, artículos, descripciones.

## ➕ Crear un Nuevo Componente

### Ejemplo: Crear un componente "Gallery" (Galería)

#### 1️⃣ Crear los campos: `components/fields/gallery.php`

```php
<?php
use Carbon_Fields\Field;

return array(
    Field::make('text', 'gallery_title', __('Título de la galería', 'binomio')),
    
    Field::make('media_gallery', 'gallery_images', __('Imágenes', 'binomio'))
        ->set_type(array('image')),
    
    Field::make('select', 'gallery_columns', __('Columnas', 'binomio'))
        ->add_options(array(
            '2' => '2 columnas',
            '3' => '3 columnas',
            '4' => '4 columnas',
        ))
        ->set_default_value('3'),
);
```

#### 2️⃣ Crear el template: `components/templates/gallery.php`

```php
<?php
$title = $component['gallery_title'] ?? '';
$images = $component['gallery_images'] ?? array();
$columns = $component['gallery_columns'] ?? '3';
?>

<section class="component-gallery component-gallery--cols-<?php echo esc_attr($columns); ?>">
    <?php if ($title): ?>
        <h2 class="component-gallery__title"><?php echo esc_html($title); ?></h2>
    <?php endif; ?>
    
    <div class="component-gallery__grid">
        <?php foreach ($images as $image_id): ?>
            <div class="component-gallery__item">
                <?php echo wp_get_attachment_image($image_id, 'large'); ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
```

#### 3️⃣ (Opcional) Crear estilos: `components/templates/gallery.css`

```css
.component-gallery {
    padding: 60px 20px;
}

.component-gallery__title {
    text-align: center;
    margin-bottom: 40px;
}

.component-gallery__grid {
    display: grid;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.component-gallery--cols-2 .component-gallery__grid {
    grid-template-columns: repeat(2, 1fr);
}

.component-gallery--cols-3 .component-gallery__grid {
    grid-template-columns: repeat(3, 1fr);
}

.component-gallery--cols-4 .component-gallery__grid {
    grid-template-columns: repeat(4, 1fr);
}

.component-gallery__item img {
    width: 100%;
    height: auto;
    display: block;
}
```

**¡Y listo!** El componente ya está disponible automáticamente en el Constructor de Página.

## 📚 Tipos de Campos de Carbon Fields

```php
// Texto simple
Field::make('text', 'mi_campo', 'Etiqueta')

// Área de texto
Field::make('textarea', 'mi_campo', 'Etiqueta')

// Editor WYSIWYG
Field::make('rich_text', 'mi_campo', 'Etiqueta')

// Imagen única
Field::make('image', 'mi_campo', 'Etiqueta')

// Galería de imágenes
Field::make('media_gallery', 'mi_campo', 'Etiqueta')

// Archivo
Field::make('file', 'mi_campo', 'Etiqueta')

// Select/Dropdown
Field::make('select', 'mi_campo', 'Etiqueta')
    ->add_options(array(
        'opcion1' => 'Opción 1',
        'opcion2' => 'Opción 2',
    ))

// Checkbox
Field::make('checkbox', 'mi_campo', 'Etiqueta')

// Color
Field::make('color', 'mi_campo', 'Etiqueta')

// Fecha
Field::make('date', 'mi_campo', 'Etiqueta')

// Campos repetibles (subgrupo)
Field::make('complex', 'mi_campo', 'Etiqueta')
    ->add_fields(array(
        Field::make('text', 'titulo', 'Título'),
        Field::make('image', 'imagen', 'Imagen'),
    ))

// Relación con posts
Field::make('association', 'mi_campo', 'Etiqueta')
    ->set_types(array(
        array('type' => 'post', 'post_type' => 'post'),
    ))
```

## 💡 Tips y Mejores Prácticas

### Nomenclatura de campos

Usa siempre un prefijo con el nombre del componente:

```php
// ✅ BIEN
Field::make('text', 'hero_title', 'Título')
Field::make('text', 'gallery_columns', 'Columnas')

// ❌ MAL
Field::make('text', 'title', 'Título')  // Demasiado genérico
```

### Acceder a los datos en el template

```php
// Los datos están en el array $component
$titulo = $component['hero_title'] ?? '';  // Con fallback

// Para imágenes
$imagen_url = $component['hero_image'] ?? '';
$imagen_id = $component['hero_image_id'] ?? 0;

// Para complex fields (arrays)
$items = $component['gallery_images'] ?? array();
foreach ($items as $item) {
    echo $item['titulo'];
}
```

### Sanitización y escape

```php
// Texto simple
<?php echo esc_html($title); ?>

// URLs
<?php echo esc_url($url); ?>

// Atributos HTML
<div class="<?php echo esc_attr($class); ?>">

// HTML permitido (rich text)
<?php echo wp_kses_post($content); ?>

// Auto-paragraphs
<?php echo wpautop($text); ?>
```

## 🎯 Casos de Uso Comunes

### Componente de Testimonios

**fields/testimonials.php:**
```php
return array(
    Field::make('complex', 'testimonials_items', 'Testimonios')
        ->add_fields(array(
            Field::make('textarea', 'quote', 'Cita'),
            Field::make('text', 'author', 'Autor'),
            Field::make('text', 'position', 'Cargo'),
            Field::make('image', 'photo', 'Foto'),
        )),
);
```

### Componente de Características

**fields/features.php:**
```php
return array(
    Field::make('text', 'features_title', 'Título'),
    Field::make('complex', 'features_items', 'Características')
        ->add_fields(array(
            Field::make('text', 'icon', 'Icono (clase CSS)'),
            Field::make('text', 'title', 'Título'),
            Field::make('textarea', 'description', 'Descripción'),
        )),
);
```

## 🔧 Troubleshooting

### "No veo el metabox en la página"

- Verifica que has seleccionado el template **"Section-based"**
- Comprueba que Carbon Fields está instalado y activado

### "El componente no aparece en la lista"

- Verifica que el archivo existe en `components/fields/`
- El nombre del archivo debe ser válido (sin espacios, minúsculas)
- Comprueba que el archivo devuelve un array con `return array(...)`

### "Los estilos no se cargan"

- El archivo CSS debe tener el mismo nombre que el componente
- Debe estar en `components/templates/`
- Limpia la caché del navegador

## 📞 Soporte

Para más información sobre Carbon Fields:
- [Documentación oficial](https://carbonfields.net/docs/)
- [Tipos de campos](https://carbonfields.net/docs/containers-usage/)

---

¡Disfruta construyendo con componentes! 🎉
