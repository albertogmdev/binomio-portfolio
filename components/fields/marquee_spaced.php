<?php
/**
 * Marquee Spaced Component Fields
 *
 * Galería en marquee infinita con espacio de 80px entre assets y tamaños
 * alternos (grande 40% / pequeña 20%). Los vídeos siempre se reproducen en loop
 * muted y todos los assets se centran verticalmente.
 */

use TranslatableCarbonFields\Fields\Field;

return Field::resolve(array(
    Field::make('checkbox', 'marquee_spaced_fullwidth', __('Fullwidth', 'binomio')),

    Field::make('media_gallery', 'marquee_spaced_assets', __('Assets', 'binomio')),
));
