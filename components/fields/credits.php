<?php
/**
 * Credits Component Fields
 *
 * Bloque de créditos: tags, contenido de créditos (WYSIWYG) y links (botón negro
 * con icono de enlace, igual que el hero).
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('set', 'credits_tags', __('Tags', 'binomio'))
        ->set_options(array(
            'branding'    => __('Branding', 'binomio'),
            'ux_ui'       => __('UX/UI', 'binomio'),
            'development' => __('Development', 'binomio'),
            'website'     => __('Website', 'binomio'),
        )),

    Field::make('rich_text', 'credits_content', __('Créditos', 'binomio')),

    Field::make('complex', 'credits_links', __('Links', 'binomio'))
        ->set_layout('tabbed-vertical')
        ->add_fields(Field::resolve(array(
            Field::make('select', 'tipo', __('Tipo de link', 'binomio'))
                ->set_options(array(
                    'web'       => __('Web', 'binomio'),
                    'brandbook' => __('Brandbook', 'binomio'),
                    'other'     => __('Otro', 'binomio'),
                ))
                ->set_default_value('web'),

            Field::make('text', 'texto', __('Texto del link', 'binomio')),

            Field::make('text', 'url', __('URL', 'binomio')),
        ))),
));
