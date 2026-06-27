<?php
/**
 * Fullgallery Component Fields
 *
 * Galería de assets (imágenes / vídeos) a ancho completo, apilados verticalmente.
 * Selector de items igual que la galería normal, con opción de loop (muted) por
 * vídeo.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('complex', 'fullgallery_items', __('Items', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(array(
            \Carbon_Fields\Field::make('image', 'asset_id', __('Imagen / Vídeo', 'binomio')),
            \Carbon_Fields\Field::make('checkbox', 'asset_loop_video', __('Loop video (muted)', 'binomio')),
        )),
));
