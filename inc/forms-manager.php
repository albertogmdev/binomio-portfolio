<?php
/**
 * Forms Manager
 *
 * - CPT bnm_form: each post is a form definition (schema via Carbon Fields).
 * - CPT bnm_form_entry: each submission stored as a post.
 * - Admin page: list all forms + submissions per form.
 * - AJAX handler: receives front-end submissions and saves entries.
 * - Shortcode [bnm_form id="X"]: renders the form on the front end.
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field;

// ---------------------------------------------------------------------------
// 1. CPT: bnm_form
// ---------------------------------------------------------------------------

add_action('init', 'bnm_register_post_type_form');
function bnm_register_post_type_form() {
    register_post_type('bnm_form', array(
        'labels' => array(
            'name'          => __('Forms', 'binomio'),
            'singular_name' => __('Form', 'binomio'),
            'add_new_item'  => __('Add new form', 'binomio'),
            'edit_item'     => __('Edit form', 'binomio'),
            'all_items'     => __('All forms', 'binomio'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => false, // hidden from default menu; shown in custom admin page
        'supports'     => array('title'),
        'menu_icon'    => 'dashicons-feedback',
    ));
}

// ---------------------------------------------------------------------------
// 2. CPT: bnm_form_entry
// ---------------------------------------------------------------------------

add_action('init', 'bnm_register_post_type_form_entry');
function bnm_register_post_type_form_entry() {
    register_post_type('bnm_form_entry', array(
        'labels' => array(
            'name'          => __('Form Entries', 'binomio'),
            'singular_name' => __('Form Entry', 'binomio'),
            'all_items'     => __('All entries', 'binomio'),
        ),
        'public'       => false,
        'show_ui'      => false,
        'supports'     => array('title'),
    ));
}

// ---------------------------------------------------------------------------
// 3. Carbon Fields: form schema
// ---------------------------------------------------------------------------

add_action('carbon_fields_register_fields', 'bnm_register_form_fields');
function bnm_register_form_fields() {
    // Non-translatable sub-fields
    $static_fields = array(
        \Carbon_Fields\Field::make('text', 'field_name', __('Name (slug)', 'binomio'))
            ->set_width(50)
            ->set_help_text(__('Lowercase, no spaces. E.g.: email', 'binomio')),
        \Carbon_Fields\Field::make('select', 'field_type', __('Type', 'binomio'))
            ->set_width(33)
            ->set_options(array(
                'text'     => __('Text', 'binomio'),
                'email'    => __('Email', 'binomio'),
                'tel'      => __('Phone', 'binomio'),
                'textarea' => __('Textarea', 'binomio'),
                'select'   => __('Select', 'binomio'),
                'checkbox' => __('Checkbox', 'binomio'),
                'hidden'   => __('Hidden', 'binomio'),
            )),
        \Carbon_Fields\Field::make('checkbox', 'field_required', __('Required', 'binomio'))
            ->set_option_value('yes')
            ->set_width(17),
    );

    // Translatable sub-fields — plugin generates base + toggle + translation fields
    $translatable_sub = Translatable::fields(array(
        Translatable_Field::make('text', 'field_label',       __('Label', 'binomio')),
        Translatable_Field::make('text', 'field_placeholder', __('Placeholder', 'binomio')),
        Translatable_Field::make('text', 'field_default',     __('Default value', 'binomio')),
        Translatable_Field::make('text', 'field_options',     __('Options (select)', 'binomio')),
    ));

    Container::make('post_meta', __('Form fields', 'binomio'))
        ->where('post_type', '=', 'bnm_form')
        ->add_fields(array(
            \Carbon_Fields\Field::make('complex', 'bnm_form_fields', __('Fields', 'binomio'))
                ->set_layout('tabbed-vertical')
                ->setup_labels(array(
                    'plural_name'   => __('Fields', 'binomio'),
                    'singular_name' => __('Field', 'binomio'),
                ))
                ->add_fields(array_merge($static_fields, $translatable_sub)),
        ));

    // Form-level translatable fields (submit label + success message)
    Container::make('post_meta', __('Form settings', 'binomio'))
        ->where('post_type', '=', 'bnm_form')
        ->add_fields(array_merge(
            Translatable::fields(array(
                Translatable_Field::make('text',     'bnm_form_submit_label',    __('Submit button label', 'binomio')),
                Translatable_Field::make('textarea', 'bnm_form_success_message', __('Success message', 'binomio')),
            )),
            array(
                \Carbon_Fields\Field::make('text', 'bnm_form_notify_email', __('Notification email', 'binomio'))
                    ->set_help_text(__('Leave empty to use the site admin email.', 'binomio')),
            )
        ));
}

// ---------------------------------------------------------------------------
// 4. Admin menu page
// ---------------------------------------------------------------------------

add_action('admin_menu', 'bnm_forms_admin_menu');
add_action('admin_enqueue_scripts', 'bnm_forms_admin_assets');
function bnm_forms_admin_assets($hook) {
    if ($hook !== 'toplevel_page_bnm-forms') {
        return;
    }
    wp_enqueue_style('bnm-admin-forms', get_stylesheet_directory_uri() . '/assets/css/admin-forms.css', array(), '1.1');
    wp_enqueue_script('bnm-admin-forms-js', get_stylesheet_directory_uri() . '/assets/js/admin-forms.js', array(), '1.0', true);
}

function bnm_forms_admin_menu() {    add_menu_page(
        __('Forms', 'binomio'),
        __('Forms', 'binomio'),
        'edit_posts',
        'bnm-forms',
        'bnm_forms_admin_page',
        'dashicons-feedback',
        30
    );

    add_submenu_page(
        'bnm-forms',
        __('All forms', 'binomio'),
        __('All forms', 'binomio'),
        'edit_posts',
        'bnm-forms',
        'bnm_forms_admin_page'
    );

    add_submenu_page(
        'bnm-forms',
        __('New form', 'binomio'),
        __('+ New form', 'binomio'),
        'edit_posts',
        'post-new.php?post_type=bnm_form'
    );
}

function bnm_forms_admin_page() {
    $view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'list';

    if ($view === 'entries' && !empty($_GET['form_id'])) {
        bnm_render_entries_page((int) $_GET['form_id']);
    } elseif ($view === 'entry' && !empty($_GET['entry_id'])) {
        bnm_render_single_entry_page((int) $_GET['entry_id']);
    } else {
        bnm_render_forms_list_page();
    }
}

function bnm_render_forms_list_page() {
    $forms = get_posts(array(
        'post_type'      => 'bnm_form',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    ?>
    <div class="wrap bnm-forms-wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e('Forms', 'binomio'); ?></h1>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=bnm_form')); ?>" class="page-title-action">
            <?php esc_html_e('+ New form', 'binomio'); ?>
        </a>
        <hr class="wp-header-end">

        <?php if (empty($forms)) : ?>
            <p><?php esc_html_e('No forms yet. Create your first one.', 'binomio'); ?></p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped bnm-forms-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Form', 'binomio'); ?></th>
                        <th><?php esc_html_e('Shortcode', 'binomio'); ?></th>
                        <th><?php esc_html_e('Fields', 'binomio'); ?></th>
                        <th><?php esc_html_e('Entries', 'binomio'); ?></th>
                        <th><?php esc_html_e('Actions', 'binomio'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($forms as $form) :
                        $fields   = carbon_get_post_meta($form->ID, 'bnm_form_fields');
                        $n_fields = is_array($fields) ? count($fields) : 0;
                        $entries  = get_posts(array(
                            'post_type'      => 'bnm_form_entry',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'meta_key'       => '_bnm_form_id',
                            'meta_value'     => $form->ID,
                            'fields'         => 'ids',
                        ));
                        $n_entries = count($entries);
                        $entries_url = add_query_arg(array('page' => 'bnm-forms', 'view' => 'entries', 'form_id' => $form->ID), admin_url('admin.php'));
                        $edit_url    = get_edit_post_link($form->ID);
                    ?>
                        <tr>
                            <td><strong><?php echo esc_html($form->post_title); ?></strong></td>
                            <td><code>[bnm_form id="<?php echo esc_html($form->ID); ?>"]</code></td>
                            <td><?php echo (int) $n_fields; ?></td>
                            <td>
                                <?php if ($n_entries > 0) : ?>
                                    <a href="<?php echo esc_url($entries_url); ?>"><?php echo (int) $n_entries; ?></a>
                                <?php else : ?>
                                    0
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($edit_url); ?>"><?php esc_html_e('Edit', 'binomio'); ?></a>
                                <?php if ($n_entries > 0) : ?>
                                    &nbsp;|&nbsp;
                                    <a href="<?php echo esc_url($entries_url); ?>"><?php esc_html_e('View entries', 'binomio'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

function bnm_render_entries_page($form_id) {
    $form = get_post($form_id);
    if (!$form || $form->post_type !== 'bnm_form') {
        echo '<div class="wrap"><p>' . esc_html__('Form not found.', 'binomio') . '</p></div>';
        return;
    }

    $entries = get_posts(array(
        'post_type'      => 'bnm_form_entry',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_bnm_form_id',
        'meta_value'     => $form_id,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    $fields_schema = carbon_get_post_meta($form_id, 'bnm_form_fields');
    $back_url = add_query_arg(array('page' => 'bnm-forms'), admin_url('admin.php'));
    ?>
    <div class="wrap bnm-forms-wrap">
        <h1>
            <a href="<?php echo esc_url($back_url); ?>"><?php esc_html_e('Forms', 'binomio'); ?></a>
            &rsaquo; <?php echo esc_html($form->post_title); ?>
        </h1>
        <hr class="wp-header-end">

        <?php if (empty($entries)) : ?>
            <p><?php esc_html_e('No entries yet.', 'binomio'); ?></p>
        <?php else : ?>
            <div class="bnm-entries-toolbar">
                <input type="search" id="bnm-entries-search" class="bnm-entries-search" placeholder="<?php esc_attr_e('Search entries…', 'binomio'); ?>" />
                <span class="bnm-entries-count">
                    <?php printf(esc_html__('%d entries', 'binomio'), count($entries)); ?>
                </span>
            </div>
            <table class="wp-list-table widefat fixed striped bnm-forms-table" id="bnm-entries-table">
                <thead>
                    <tr>
                        <th class="bnm-sortable" data-sort="date" data-sort-dir="desc"><?php esc_html_e('Date', 'binomio'); ?> <span class="bnm-sort-icon">&#9660;</span></th>
                        <?php if (is_array($fields_schema)) : ?>
                            <?php foreach ($fields_schema as $field) : ?>
                                <?php if (($field['field_type'] ?? '') === 'hidden') continue; ?>
                                <th class="bnm-sortable" data-sort="text"><?php echo esc_html($field['field_label'] ?? $field['field_name'] ?? ''); ?> <span class="bnm-sort-icon"></span></th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <th><?php esc_html_e('Actions', 'binomio'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry) :
                        $data = get_post_meta($entry->ID, '_bnm_entry_data', true);
                        $data = is_array($data) ? $data : array();
                        $entry_url = add_query_arg(array('page' => 'bnm-forms', 'view' => 'entry', 'entry_id' => $entry->ID), admin_url('admin.php'));
                    ?>
                        <tr data-date="<?php echo esc_attr(get_the_date('Y-m-d H:i:s', $entry->ID)); ?>">
                            <td><?php echo esc_html(get_the_date('d/m/Y H:i', $entry->ID)); ?></td>
                            <?php if (is_array($fields_schema)) : ?>
                                <?php foreach ($fields_schema as $field) : ?>
                                    <?php if (($field['field_type'] ?? '') === 'hidden') continue; ?>
                                    <td><?php echo esc_html($data[$field['field_name']] ?? '—'); ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <td><a href="<?php echo esc_url($entry_url); ?>"><?php esc_html_e('View', 'binomio'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="bnm-no-results" style="display:none;"><?php esc_html_e('No entries match your search.', 'binomio'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function bnm_render_single_entry_page($entry_id) {
    $entry = get_post($entry_id);
    if (!$entry || $entry->post_type !== 'bnm_form_entry') {
        echo '<div class="wrap"><p>' . esc_html__('Entry not found.', 'binomio') . '</p></div>';
        return;
    }

    $form_id = (int) get_post_meta($entry_id, '_bnm_form_id', true);
    $data    = get_post_meta($entry_id, '_bnm_entry_data', true);
    $data    = is_array($data) ? $data : array();

    $back_url = add_query_arg(array('page' => 'bnm-forms', 'view' => 'entries', 'form_id' => $form_id), admin_url('admin.php'));
    ?>
    <div class="wrap bnm-forms-wrap">
        <h1>
            <a href="<?php echo esc_url(add_query_arg('page', 'bnm-forms', admin_url('admin.php'))); ?>"><?php esc_html_e('Forms', 'binomio'); ?></a>
            &rsaquo; <a href="<?php echo esc_url($back_url); ?>"><?php echo esc_html(get_the_title($form_id)); ?></a>
            &rsaquo; <?php echo esc_html(get_the_date('d/m/Y H:i', $entry_id)); ?>
        </h1>
        <hr class="wp-header-end">

        <table class="widefat bnm-forms-table bnm-entry-detail">
            <thead>
                <tr>
                    <th><?php esc_html_e('Field', 'binomio'); ?></th>
                    <th><?php esc_html_e('Value', 'binomio'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $key => $value) : ?>
                    <tr>
                        <td><strong><?php echo esc_html($key); ?></strong></td>
                        <td><?php echo nl2br(esc_html((string) $value)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top:16px;">
            <strong><?php esc_html_e('IP:', 'binomio'); ?></strong>
            <?php echo esc_html(get_post_meta($entry_id, '_bnm_entry_ip', true)); ?>
        </p>
    </div>
    <?php
}

// ---------------------------------------------------------------------------
// 5. AJAX: save form submission
// ---------------------------------------------------------------------------

add_action('wp_ajax_bnm_submit_form', 'bnm_handle_form_submission');
add_action('wp_ajax_nopriv_bnm_submit_form', 'bnm_handle_form_submission');

function bnm_handle_form_submission() {
    // Nonce check
    if (empty($_POST['bnm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bnm_nonce'])), 'bnm_form_submit')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'binomio')));
    }

    $form_id = isset($_POST['bnm_form_id']) ? (int) $_POST['bnm_form_id'] : 0;

    if (!$form_id || get_post_type($form_id) !== 'bnm_form') {
        wp_send_json_error(array('message' => __('Invalid form.', 'binomio')));
    }

    $fields_schema = carbon_get_post_meta($form_id, 'bnm_form_fields');

    if (!is_array($fields_schema) || empty($fields_schema)) {
        wp_send_json_error(array('message' => __('Form has no fields.', 'binomio')));
    }

    $entry_data = array();
    $errors     = array();

    foreach ($fields_schema as $field) {
        $name     = sanitize_key($field['field_name'] ?? '');
        $type     = $field['field_type'] ?? 'text';
        $required = ($field['field_required'] ?? '') === 'yes';

        if ($name === '') {
            continue;
        }

        $raw_value = isset($_POST['bnm_field'][$name]) ? wp_unslash($_POST['bnm_field'][$name]) : '';

        if ($type === 'textarea') {
            $value = sanitize_textarea_field((string) $raw_value);
        } elseif ($type === 'email') {
            $value = sanitize_email((string) $raw_value);
            if ($value === '' && $required) {
                $errors[$name] = __('Invalid email.', 'binomio');
            }
        } elseif ($type === 'checkbox') {
            $value = !empty($raw_value) ? '1' : '0';
        } else {
            $value = sanitize_text_field((string) $raw_value);
        }

        if ($required && ($value === '' || $value === '0') && $type === 'checkbox') {
            // checkboxes are not strictly required
        } elseif ($required && trim((string) $value) === '') {
            /* translators: %s: field label */
            $errors[$name] = sprintf(__('%s is required.', 'binomio'), $field['field_label'] ?? $name);
        }

        $entry_data[$name] = $value;
    }

    if (!empty($errors)) {
        wp_send_json_error(array('message' => __('Please fix the errors below.', 'binomio'), 'errors' => $errors));
    }

    // Save entry
    $entry_id = wp_insert_post(array(
        'post_type'   => 'bnm_form_entry',
        'post_status' => 'publish',
        'post_title'  => get_the_title($form_id) . ' — ' . current_time('mysql'),
    ));

    if (is_wp_error($entry_id)) {
        wp_send_json_error(array('message' => __('Could not save entry.', 'binomio')));
    }

    update_post_meta($entry_id, '_bnm_form_id', $form_id);
    update_post_meta($entry_id, '_bnm_entry_data', $entry_data);
    update_post_meta($entry_id, '_bnm_entry_ip', sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''));

    // Optional email notification
    $notify_email = carbon_get_post_meta($form_id, 'bnm_form_notify_email');
    if (empty($notify_email)) {
        $notify_email = get_option('admin_email');
    }

    if (is_email($notify_email)) {
        $subject = sprintf(__('[%s] New form entry: %s', 'binomio'), get_bloginfo('name'), get_the_title($form_id));
        $body    = '';
        foreach ($entry_data as $key => $val) {
            $body .= $key . ': ' . $val . "\n";
        }
        wp_mail($notify_email, $subject, $body);
    }

    $success_message = tcf_meta($form_id, 'bnm_form_success_message')
        ?: bnm_t('form_success', 'Thank you! We will get back to you shortly.');

    wp_send_json_success(array('message' => $success_message));
}

// ---------------------------------------------------------------------------
// 6. Shortcode [bnm_form id="X"]
// ---------------------------------------------------------------------------

add_shortcode('bnm_form', 'bnm_render_form_shortcode');

function bnm_render_form_shortcode($atts) {
    $atts = shortcode_atts(array('id' => 0), $atts, 'bnm_form');
    $form_id = (int) $atts['id'];

    if (!$form_id || get_post_type($form_id) !== 'bnm_form') {
        return '';
    }

    $fields_schema  = carbon_get_post_meta($form_id, 'bnm_form_fields');
    $submit_label   = tcf_meta($form_id, 'bnm_form_submit_label') ?: bnm_t('form_submit', 'Send');

    if (!is_array($fields_schema) || empty($fields_schema)) {
        return '';
    }

    ob_start();
    ?>
    <form
        class="bnm-form"
        data-form-id="<?php echo esc_attr($form_id); ?>"
        novalidate
    >
        <?php wp_nonce_field('bnm_form_submit', 'bnm_nonce'); ?>
        <input type="hidden" name="bnm_form_id" value="<?php echo esc_attr($form_id); ?>">

        <div class="bnm-form__fields">
            <?php foreach ($fields_schema as $field) :
                $name = sanitize_key($field['field_name'] ?? '');
                if ($name === '') continue;

                $label       = esc_html(tcf_item($field, 'field_label') ?: $name);
                $placeholder = esc_attr(tcf_item($field, 'field_placeholder'));
                $default     = esc_attr(tcf_item($field, 'field_default'));
                $options_raw = tcf_item($field, 'field_options');
                $type        = $field['field_type'] ?? 'text';
                $required    = ($field['field_required'] ?? '') === 'yes';
                $req_attr    = $required ? 'required' : '';
            ?>
                <div class="bnm-form__field bnm-form__field--<?php echo esc_attr($type); ?>">
                    <?php if ($type === 'hidden') : ?>
                        <input type="hidden" name="bnm_field[<?php echo esc_attr($name); ?>]" value="<?php echo $default; ?>">

                    <?php elseif ($type === 'textarea') : ?>
                        <label class="bnm-form__label" for="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>">
                            <?php echo $label; ?><?php if ($required) echo ' <span aria-hidden="true">*</span>'; ?>
                        </label>
                        <textarea
                            id="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>"
                            class="bnm-form__input input"
                            name="bnm_field[<?php echo esc_attr($name); ?>]"
                            placeholder="<?php echo $placeholder; ?>"
                            <?php echo $req_attr; ?>
                            rows="5"
                        ><?php echo $default; ?></textarea>

                    <?php elseif ($type === 'select') :
                        $options = array_filter(array_map('trim', explode(',', $options_raw)));
                    ?>
                        <label class="bnm-form__label" for="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>">
                            <?php echo $label; ?><?php if ($required) echo ' <span aria-hidden="true">*</span>'; ?>
                        </label>
                        <select
                            id="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>"
                            class="bnm-form__input input"
                            name="bnm_field[<?php echo esc_attr($name); ?>]"
                            <?php echo $req_attr; ?>
                        >
                            <option value=""><?php echo esc_html(bnm_t('form_select_default', '— Select —')); ?></option>
                            <?php foreach ($options as $opt) : ?>
                                <option value="<?php echo esc_attr($opt); ?>" <?php selected($default, $opt); ?>>
                                    <?php echo esc_html($opt); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    <?php elseif ($type === 'checkbox') : ?>
                        <label class="bnm-form__label bnm-form__label--checkbox">
                            <input
                                type="checkbox"
                                class="bnm-form__checkbox"
                                name="bnm_field[<?php echo esc_attr($name); ?>]"
                                value="1"
                                <?php echo $req_attr; ?>
                            >
                            <?php echo $label; ?><?php if ($required) echo ' <span aria-hidden="true">*</span>'; ?>
                        </label>

                    <?php else : ?>
                        <label class="bnm-form__label" for="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>">
                            <?php echo $label; ?><?php if ($required) echo ' <span aria-hidden="true">*</span>'; ?>
                        </label>
                        <input
                            type="<?php echo esc_attr($type); ?>"
                            id="bnm_<?php echo esc_attr($form_id . '_' . $name); ?>"
                            class="bnm-form__input input"
                            name="bnm_field[<?php echo esc_attr($name); ?>]"
                            placeholder="<?php echo $placeholder; ?>"
                            value="<?php echo $default; ?>"
                            <?php echo $req_attr; ?>
                        >
                    <?php endif; ?>
                    <span class="bnm-form__error" data-field="<?php echo esc_attr($name); ?>"></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="bnm-form__footer">
            <button type="submit" class="bnm-form__submit button">
                <?php echo esc_html($submit_label); ?>
            </button>
            <span class="bnm-form__spinner" aria-hidden="true"></span>
        </div>

        <div class="bnm-form__feedback" role="alert" aria-live="polite"></div>
    </form>

    <script>
    (function () {
        var form = document.querySelector('.bnm-form[data-form-id="<?php echo esc_js($form_id); ?>"]');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            form.querySelectorAll('.bnm-form__error').forEach(function (el) { el.textContent = ''; });
            form.querySelector('.bnm-form__feedback').textContent = '';
            form.querySelector('.bnm-form__submit').disabled = true;
            form.querySelector('.bnm-form__spinner').style.display = 'inline-block';

            var data = new FormData(form);
            data.append('action', 'bnm_submit_form');

            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                method: 'POST',
                body: data,
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                form.querySelector('.bnm-form__spinner').style.display = 'none';
                form.querySelector('.bnm-form__submit').disabled = false;

                if (res.success) {
                    form.querySelector('.bnm-form__feedback').textContent = res.data.message;
                    form.querySelector('.bnm-form__feedback').classList.add('bnm-form__feedback--success');
                    form.reset();
                } else {
                    form.querySelector('.bnm-form__feedback').textContent = res.data.message || '';
                    if (res.data.errors) {
                        Object.keys(res.data.errors).forEach(function (field) {
                            var el = form.querySelector('.bnm-form__error[data-field="' + field + '"]');
                            if (el) el.textContent = res.data.errors[field];
                        });
                    }
                }
            })
            .catch(function () {
                form.querySelector('.bnm-form__spinner').style.display = 'none';
                form.querySelector('.bnm-form__submit').disabled = false;
                form.querySelector('.bnm-form__feedback').textContent = '<?php echo esc_js(bnm_t('form_error_generic', 'An error occurred. Please try again.')); ?>';
            });
        });
    }());
    </script>
    <?php
    return ob_get_clean();
}
