<?php
/**
 * Form Component Fields
 *
 * Allows inserting any bnm_form into a page via the component builder.
 */

use TranslatableCarbonFields\Fields\Field;

// Build options list from published bnm_form posts
$form_options = array('' => __('— Select a form —', 'binomio'));
$forms = get_posts(array(
    'post_type'      => 'bnm_form',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
));
foreach ($forms as $f) {
    $form_options[$f->ID] = $f->post_title;
}

return Field::resolve(array(
    Field::make('select', 'form_id', __('Form', 'binomio'))
        ->set_options($form_options),
));
