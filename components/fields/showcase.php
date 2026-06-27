<?php
/**
 * Showcase Component Fields
 *
 * Bloque centrado: icono/imagen, título, descripción y un asset (imagen / vídeo /
 * gif) con opciones de loop (muted), ancho máximo y aspect ratio.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('image', 'showcase_icon', __('Icono / Imagen (título)', 'binomio')),

    Field::make('text', 'showcase_title', __('Título', 'binomio')),

    Field::make('rich_text', 'showcase_description', __('Descripción', 'binomio')),

    Field::make('file', 'showcase_asset', __('Asset (imagen / vídeo / gif)', 'binomio')),

    Field::make('checkbox', 'showcase_asset_loop', __('Loop video (muted)', 'binomio')),

    Field::make('text', 'showcase_asset_max_width', __('Ancho máximo del asset (px)', 'binomio'))
        ->set_attribute('type', 'number')
        ->set_attribute('min', '0')
        ->set_attribute('placeholder', 'ej: 800'),

    Field::make('text', 'showcase_asset_aspect', __('Aspect ratio del asset', 'binomio'))
        ->set_attribute('placeholder', 'ej: 16/9'),
));
