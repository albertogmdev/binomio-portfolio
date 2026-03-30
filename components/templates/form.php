<?php
/**
 * Form Component Template
 */

$form_id = isset($component['form_id']) ? (int) $component['form_id'] : 0;

if (!$form_id) {
    return;
}

echo do_shortcode('[bnm_form id="' . $form_id . '"]');
