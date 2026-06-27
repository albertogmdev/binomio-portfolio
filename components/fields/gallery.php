<?php
/**
 * Gallery Component Fields
 *
 * Galería en grid. Cada item puede ser imagen, gif o vídeo y marcarse como
 * fullwidth (ocupa las 2 columnas) y/o como vídeo en loop (muted).
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('checkbox', 'gallery_remove_column_space', __('Remove column space', 'binomio')),

    Field::make('complex', 'gallery_items', __('Items', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(array(
            \Carbon_Fields\Field::make('image', 'asset_id', __('Imagen / Vídeo', 'binomio')),
            \Carbon_Fields\Field::make('checkbox', 'asset_fullwidth', __('Fullwidth (2 col)', 'binomio')),
            \Carbon_Fields\Field::make('checkbox', 'asset_loop_video', __('Loop video (muted)', 'binomio')),
        )),
));
