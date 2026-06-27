<?php
/**
 * Title + Text Component Fields
 *
 * Componente global: un título (se muestra en mayúsculas) y un contenido WYSIWYG.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('text', 'title_text_title', __('Título', 'binomio')),

    Field::make('rich_text', 'title_text_content', __('Contenido', 'binomio')),
));
