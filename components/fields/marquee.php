<?php
/**
 * Marquee Component Fields
 *
 * Galería en marquee infinita de imágenes / gifs / vídeos. Los vídeos siempre se
 * reproducen en loop muted. Cada asset ocupa el 25% del ancho del container.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('checkbox', 'marquee_fullwidth', __('Fullwidth', 'binomio')),

    Field::make('media_gallery', 'marquee_assets', __('Assets', 'binomio')),
));
