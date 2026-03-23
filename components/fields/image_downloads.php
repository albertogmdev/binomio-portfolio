<?php
/**
 * Image Downloads Component Fields
 */

use Carbon_Fields\Field;

return array(
    Field::make('text', 'section_title', __('Titulo de la seccion', 'binomio')),
    Field::make('rich_text', 'section_description', __('Descripcion', 'binomio')),
    Field::make('complex', 'gallery_items', __('Items de galeria', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(array(
            Field::make('text', 'item_title', __('Titulo del item', 'binomio')),
            Field::make('image', 'preview_image', __('Imagen de muestra', 'binomio')),
            Field::make('rich_text', 'item_description', __('Descripcion del item', 'binomio')),
            Field::make('complex', 'download_formats', __('Formatos para descargar', 'binomio'))
                ->set_layout('tabbed-vertical')
                ->add_fields(array(
                    Field::make('text', 'format_label', __('Nombre del formato', 'binomio')),
                    Field::make('text', 'format_note', __('Nota (opcional)', 'binomio')),
                    Field::make('file', 'format_file', __('Archivo', 'binomio')),
                )),
        )),
);
