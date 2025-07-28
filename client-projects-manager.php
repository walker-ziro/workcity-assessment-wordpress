<?php
/**
 * Plugin Name: Workcity Client Project Manager
 * Description: A plugin to manage client projects with custom post type, meta fields, and shortcode display.
 * Version: 1.2.0
 * Author: Ezekiel Success
 * Text Domain: workcity-client-project-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Activation hook to flush rewrite rules
register_activation_hook(__FILE__, 'wcp_activation');
function wcp_activation() {
    // Register post type and taxonomy
    wcp_register_client_project_post_type();
    wcp_register_project_categories();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wcp_deactivation');
function wcp_deactivation() {
    flush_rewrite_rules();
}

// Define plugin constants
define('WCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCP_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Initialize the plugin hooks and actions
 */
function wcp_init_plugin() {
    // Register custom post type
    add_action('init', 'wcp_register_client_project_post_type');
    
    // Register custom taxonomy
    add_action('init', 'wcp_register_project_categories');
    
    // Add meta boxes
    add_action('add_meta_boxes', 'wcp_add_project_meta_boxes');
    
    // Save meta fields
    add_action('save_post', 'wcp_save_project_meta_fields');
    
    // Register shortcode
    add_shortcode('display_projects', 'wcp_display_projects_shortcode');
    
    // Enqueue styles and scripts
    add_action('wp_enqueue_scripts', 'wcp_enqueue_styles');
    add_action('admin_enqueue_scripts', 'wcp_enqueue_admin_scripts');
    
    // Add dashboard widget
    add_action('wp_dashboard_setup', 'wcp_add_dashboard_widget');
}
add_action('plugins_loaded', 'wcp_init_plugin');
    
/**
 * Register Client Project custom post type
 * Creates a custom post type with slug 'client_project'
 */
function wcp_register_client_project_post_type() {
    $labels = array(
        'name'                  => _x('Client Projects', 'Post type general name', 'workcity-client-project-manager'),
        'singular_name'         => _x('Client Project', 'Post type singular name', 'workcity-client-project-manager'),
        'menu_name'             => _x('Client Projects', 'Admin Menu text', 'workcity-client-project-manager'),
        'name_admin_bar'        => _x('Client Project', 'Add New on Toolbar', 'workcity-client-project-manager'),
        'add_new'               => __('Add New', 'workcity-client-project-manager'),
        'add_new_item'          => __('Add New Client Project', 'workcity-client-project-manager'),
        'new_item'              => __('New Client Project', 'workcity-client-project-manager'),
        'edit_item'             => __('Edit Client Project', 'workcity-client-project-manager'),
        'view_item'             => __('View Client Project', 'workcity-client-project-manager'),
        'all_items'             => __('All Client Projects', 'workcity-client-project-manager'),
        'search_items'          => __('Search Client Projects', 'workcity-client-project-manager'),
        'parent_item_colon'     => __('Parent Client Projects:', 'workcity-client-project-manager'),
        'not_found'             => __('No client projects found.', 'workcity-client-project-manager'),
        'not_found_in_trash'    => __('No client projects found in Trash.', 'workcity-client-project-manager'),
        'featured_image'        => _x('Project Featured Image', 'Overrides the "Featured Image" phrase', 'workcity-client-project-manager'),
        'set_featured_image'    => _x('Set project image', 'Overrides the "Set featured image" phrase', 'workcity-client-project-manager'),
        'remove_featured_image' => _x('Remove project image', 'Overrides the "Remove featured image" phrase', 'workcity-client-project-manager'),
        'use_featured_image'    => _x('Use as project image', 'Overrides the "Use as featured image" phrase', 'workcity-client-project-manager'),
        'archives'              => _x('Client Project archives', 'The post type archive label', 'workcity-client-project-manager'),
        'insert_into_item'      => _x('Insert into client project', 'Overrides the "Insert into post" phrase', 'workcity-client-project-manager'),
        'uploaded_to_this_item' => _x('Uploaded to this client project', 'Overrides the "Uploaded to this post" phrase', 'workcity-client-project-manager'),
        'filter_items_list'     => _x('Filter client projects list', 'Screen reader text for the filter links', 'workcity-client-project-manager'),
        'items_list_navigation' => _x('Client projects list navigation', 'Screen reader text for the pagination', 'workcity-client-project-manager'),
        'items_list'            => _x('Client projects list', 'Screen reader text for the items list', 'workcity-client-project-manager'),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'client-project'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor'), // Only Title and Editor as required
        'show_in_rest'       => true,
    );
    
    register_post_type('client_project', $args);
}

/**
 * Register Project Categories custom taxonomy
 * Creates a taxonomy for categorizing projects
 */
function wcp_register_project_categories() {
    $labels = array(
        'name'              => _x('Project Categories', 'taxonomy general name', 'workcity-client-project-manager'),
        'singular_name'     => _x('Project Category', 'taxonomy singular name', 'workcity-client-project-manager'),
        'search_items'      => __('Search Project Categories', 'workcity-client-project-manager'),
        'all_items'         => __('All Project Categories', 'workcity-client-project-manager'),
        'parent_item'       => __('Parent Project Category', 'workcity-client-project-manager'),
        'parent_item_colon' => __('Parent Project Category:', 'workcity-client-project-manager'),
        'edit_item'         => __('Edit Project Category', 'workcity-client-project-manager'),
        'update_item'       => __('Update Project Category', 'workcity-client-project-manager'),
        'add_new_item'      => __('Add New Project Category', 'workcity-client-project-manager'),
        'new_item_name'     => __('New Project Category Name', 'workcity-client-project-manager'),
        'menu_name'         => __('Categories', 'workcity-client-project-manager'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('project_category', array('client_project'), $args);
}

/**
 * Add meta boxes for Project Details
 * Creates meta boxes with custom fields for client projects
 */
function wcp_add_project_meta_boxes() {
    add_meta_box(
        'wcp_project_details',
        __('Project Details', 'workcity-client-project-manager'),
        'wcp_project_details_callback',
        'client_project',
        'normal',
        'high'
    );
    
    add_meta_box(
        'wcp_client_details',
        __('Client Information', 'workcity-client-project-manager'),
        'wcp_client_details_callback',
        'client_project',
        'normal',
        'high'
    );
    
    add_meta_box(
        'wcp_project_budget',
        __('Budget & Progress', 'workcity-client-project-manager'),
        'wcp_project_budget_callback',
        'client_project',
        'side',
        'default'
    );
}

/**
 * Meta box callback function for Project Details
 * Displays the basic project fields form in the admin
 */
function wcp_project_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('wcp_project_meta_box', 'wcp_project_meta_box_nonce');
    
    // Get current values
    $project_status = get_post_meta($post->ID, '_wcp_project_status', true);
    $project_deadline = get_post_meta($post->ID, '_wcp_project_deadline', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="wcp_project_status"><?php _e('Status', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <select id="wcp_project_status" name="wcp_project_status">
                    <option value=""><?php _e('Select Status', 'workcity-client-project-manager'); ?></option>
                    <option value="not_started" <?php selected($project_status, 'not_started'); ?>><?php _e('Not Started', 'workcity-client-project-manager'); ?></option>
                    <option value="in_progress" <?php selected($project_status, 'in_progress'); ?>><?php _e('In Progress', 'workcity-client-project-manager'); ?></option>
                    <option value="completed" <?php selected($project_status, 'completed'); ?>><?php _e('Completed', 'workcity-client-project-manager'); ?></option>
                    <option value="on_hold" <?php selected($project_status, 'on_hold'); ?>><?php _e('On Hold', 'workcity-client-project-manager'); ?></option>
                </select>
                <p class="description"><?php _e('Select the current status of the project.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_project_deadline"><?php _e('Deadline', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="date" id="wcp_project_deadline" name="wcp_project_deadline" value="<?php echo esc_attr($project_deadline); ?>" />
                <p class="description"><?php _e('Set the deadline for this project.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Meta box callback function for Client Details
 * Displays the client information fields form in the admin
 */
function wcp_client_details_callback($post) {
    // Get current values
    $client_name = get_post_meta($post->ID, '_wcp_client_name', true);
    $client_email = get_post_meta($post->ID, '_wcp_client_email', true);
    $client_phone = get_post_meta($post->ID, '_wcp_client_phone', true);
    $client_company = get_post_meta($post->ID, '_wcp_client_company', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="wcp_client_name"><?php _e('Client Name', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="text" id="wcp_client_name" name="wcp_client_name" value="<?php echo esc_attr($client_name); ?>" size="50" />
                <p class="description"><?php _e('Enter the client name for this project.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_client_company"><?php _e('Company', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="text" id="wcp_client_company" name="wcp_client_company" value="<?php echo esc_attr($client_company); ?>" size="50" />
                <p class="description"><?php _e('Enter the client\'s company name.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_client_email"><?php _e('Email', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="email" id="wcp_client_email" name="wcp_client_email" value="<?php echo esc_attr($client_email); ?>" size="50" />
                <p class="description"><?php _e('Enter the client\'s email address.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_client_phone"><?php _e('Phone', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="tel" id="wcp_client_phone" name="wcp_client_phone" value="<?php echo esc_attr($client_phone); ?>" size="30" />
                <p class="description"><?php _e('Enter the client\'s phone number.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Meta box callback function for Budget & Progress
 * Displays budget and progress fields in the admin sidebar
 */
function wcp_project_budget_callback($post) {
    // Get current values
    $project_budget = get_post_meta($post->ID, '_wcp_project_budget', true);
    $project_actual_cost = get_post_meta($post->ID, '_wcp_project_actual_cost', true);
    $project_progress = get_post_meta($post->ID, '_wcp_project_progress', true);
    $project_currency = get_post_meta($post->ID, '_wcp_project_currency', true);
    
    if (empty($project_currency)) {
        $project_currency = 'USD';
    }
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="wcp_project_currency"><?php _e('Currency', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <select id="wcp_project_currency" name="wcp_project_currency">
                    <option value="USD" <?php selected($project_currency, 'USD'); ?>>USD ($)</option>
                    <option value="EUR" <?php selected($project_currency, 'EUR'); ?>>EUR (€)</option>
                    <option value="GBP" <?php selected($project_currency, 'GBP'); ?>>GBP (£)</option>
                    <option value="NGN" <?php selected($project_currency, 'NGN'); ?>>NGN (₦)</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_project_budget"><?php _e('Budget', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="number" id="wcp_project_budget" name="wcp_project_budget" value="<?php echo esc_attr($project_budget); ?>" step="0.01" min="0" />
                <p class="description"><?php _e('Enter the project budget.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_project_actual_cost"><?php _e('Actual Cost', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="number" id="wcp_project_actual_cost" name="wcp_project_actual_cost" value="<?php echo esc_attr($project_actual_cost); ?>" step="0.01" min="0" />
                <p class="description"><?php _e('Enter the actual project cost.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wcp_project_progress"><?php _e('Progress (%)', 'workcity-client-project-manager'); ?></label>
            </th>
            <td>
                <input type="range" id="wcp_project_progress" name="wcp_project_progress" value="<?php echo esc_attr($project_progress ? $project_progress : 0); ?>" min="0" max="100" oninput="this.nextElementSibling.value = this.value" />
                <output><?php echo esc_html($project_progress ? $project_progress : 0); ?></output>%
                <p class="description"><?php _e('Set the project completion percentage.', 'workcity-client-project-manager'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta fields data
 * Handles saving of custom fields with proper validation and security
 */
function wcp_save_project_meta_fields($post_id) {
    // Check if nonce is valid for security
    if (!isset($_POST['wcp_project_meta_box_nonce']) || !wp_verify_nonce($_POST['wcp_project_meta_box_nonce'], 'wcp_project_meta_box')) {
        return;
    }
    
    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check if the post type is correct
    if (get_post_type($post_id) !== 'client_project') {
        return;
    }
    
    // Save meta fields with proper sanitization
    if (isset($_POST['wcp_client_name'])) {
        update_post_meta($post_id, '_wcp_client_name', sanitize_text_field($_POST['wcp_client_name']));
    }
    
    if (isset($_POST['wcp_client_email'])) {
        update_post_meta($post_id, '_wcp_client_email', sanitize_email($_POST['wcp_client_email']));
    }
    
    if (isset($_POST['wcp_client_phone'])) {
        update_post_meta($post_id, '_wcp_client_phone', sanitize_text_field($_POST['wcp_client_phone']));
    }
    
    if (isset($_POST['wcp_client_company'])) {
        update_post_meta($post_id, '_wcp_client_company', sanitize_text_field($_POST['wcp_client_company']));
    }
    
    if (isset($_POST['wcp_project_status'])) {
        $allowed_statuses = array('not_started', 'in_progress', 'completed', 'on_hold');
        $status = sanitize_text_field($_POST['wcp_project_status']);
        if (in_array($status, $allowed_statuses) || empty($status)) {
            update_post_meta($post_id, '_wcp_project_status', $status);
        }
    }
    
    if (isset($_POST['wcp_project_deadline'])) {
        $deadline = sanitize_text_field($_POST['wcp_project_deadline']);
        // Validate date format
        if (empty($deadline) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline)) {
            update_post_meta($post_id, '_wcp_project_deadline', $deadline);
        }
    }
    
    if (isset($_POST['wcp_project_budget'])) {
        $budget = floatval($_POST['wcp_project_budget']);
        if ($budget >= 0) {
            update_post_meta($post_id, '_wcp_project_budget', $budget);
        }
    }
    
    if (isset($_POST['wcp_project_actual_cost'])) {
        $actual_cost = floatval($_POST['wcp_project_actual_cost']);
        if ($actual_cost >= 0) {
            update_post_meta($post_id, '_wcp_project_actual_cost', $actual_cost);
        }
    }
    
    if (isset($_POST['wcp_project_progress'])) {
        $progress = intval($_POST['wcp_project_progress']);
        if ($progress >= 0 && $progress <= 100) {
            update_post_meta($post_id, '_wcp_project_progress', $progress);
        }
    }
    
    if (isset($_POST['wcp_project_currency'])) {
        $allowed_currencies = array('USD', 'EUR', 'GBP', 'NGN');
        $currency = sanitize_text_field($_POST['wcp_project_currency']);
        if (in_array($currency, $allowed_currencies)) {
            update_post_meta($post_id, '_wcp_project_currency', $currency);
        }
    }
}

/**
 * Shortcode function to display all published client projects
 * Usage: [display_projects] with various filtering options
 */
function wcp_display_projects_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(array(
        'limit' => -1, // Show all projects by default
        'order' => 'DESC',
        'orderby' => 'date',
        'status' => '',
        'category' => '',
        'progress_min' => '',
        'progress_max' => '',
        'layout' => 'grid' // grid or list
    ), $atts);
    
    // Query for client projects
    $args = array(
        'post_type' => 'client_project',
        'posts_per_page' => intval($atts['limit']),
        'post_status' => 'publish',
        'order' => sanitize_text_field($atts['order']),
        'orderby' => sanitize_text_field($atts['orderby'])
    );
    
    // Add meta query for status filter
    $meta_query = array();
    if (!empty($atts['status'])) {
        $meta_query[] = array(
            'key' => '_wcp_project_status',
            'value' => sanitize_text_field($atts['status']),
            'compare' => '='
        );
    }
    
    // Add meta query for progress filters
    if (!empty($atts['progress_min'])) {
        $meta_query[] = array(
            'key' => '_wcp_project_progress',
            'value' => intval($atts['progress_min']),
            'compare' => '>='
        );
    }
    
    if (!empty($atts['progress_max'])) {
        $meta_query[] = array(
            'key' => '_wcp_project_progress',
            'value' => intval($atts['progress_max']),
            'compare' => '<='
        );
    }
    
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
        if (count($meta_query) > 1) {
            $args['meta_query']['relation'] = 'AND';
        }
    }
    
    // Add taxonomy query for category filter
    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($atts['category']),
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) {
        $layout_class = $atts['layout'] === 'list' ? 'wcp-projects-list-view' : 'wcp-projects-grid';
        echo '<div class="wcp-projects-list ' . esc_attr($layout_class) . '">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            // Get meta fields
            $client_name = get_post_meta(get_the_ID(), '_wcp_client_name', true);
            $client_company = get_post_meta(get_the_ID(), '_wcp_client_company', true);
            $client_email = get_post_meta(get_the_ID(), '_wcp_client_email', true);
            $project_status = get_post_meta(get_the_ID(), '_wcp_project_status', true);
            $project_deadline = get_post_meta(get_the_ID(), '_wcp_project_deadline', true);
            $project_progress = get_post_meta(get_the_ID(), '_wcp_project_progress', true);
            $project_budget = get_post_meta(get_the_ID(), '_wcp_project_budget', true);
            $project_currency = get_post_meta(get_the_ID(), '_wcp_project_currency', true);
            
            // Get project categories
            $categories = get_the_terms(get_the_ID(), 'project_category');
            
            // Format deadline
            $formatted_deadline = '';
            if ($project_deadline) {
                $formatted_deadline = date('F j, Y', strtotime($project_deadline));
                $days_until_deadline = floor((strtotime($project_deadline) - time()) / (60 * 60 * 24));
            }
            
            // Status display names
            $status_names = array(
                'not_started' => __('Not Started', 'workcity-client-project-manager'),
                'in_progress' => __('In Progress', 'workcity-client-project-manager'),
                'completed' => __('Completed', 'workcity-client-project-manager'),
                'on_hold' => __('On Hold', 'workcity-client-project-manager')
            );
            
            $status_display = isset($status_names[$project_status]) ? $status_names[$project_status] : ucfirst(str_replace('_', ' ', $project_status));
            
            // Currency symbols
            $currency_symbols = array(
                'USD' => '$',
                'EUR' => '€',
                'GBP' => '£',
                'NGN' => '₦'
            );
            $currency_symbol = isset($currency_symbols[$project_currency]) ? $currency_symbols[$project_currency] : '$';
            
            ?>
            <div class="wcp-project-item status-<?php echo esc_attr($project_status); ?> <?php echo $project_progress == 100 ? 'completed-progress' : ''; ?>">
                
                <?php if ($categories && !is_wp_error($categories)) : ?>
                    <div class="wcp-project-categories">
                        <?php foreach ($categories as $category) : ?>
                            <span class="wcp-category-tag"><?php echo esc_html($category->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <h3 class="wcp-project-title"><?php echo esc_html(get_the_title()); ?></h3>
                
                <div class="wcp-project-meta">
                    <?php if ($client_name) : ?>
                        <div class="wcp-client-name">
                            <strong><?php _e('Client:', 'workcity-client-project-manager'); ?></strong> 
                            <?php echo esc_html($client_name); ?>
                            <?php if ($client_company) : ?>
                                <span class="wcp-client-company"> - <?php echo esc_html($client_company); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($project_status) : ?>
                        <div class="wcp-project-status">
                            <strong><?php _e('Status:', 'workcity-client-project-manager'); ?></strong> 
                            <span class="wcp-status-badge status-<?php echo esc_attr($project_status); ?>">
                                <?php echo esc_html($status_display); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($project_progress !== '') : ?>
                        <div class="wcp-project-progress">
                            <strong><?php _e('Progress:', 'workcity-client-project-manager'); ?></strong>
                            <div class="wcp-progress-bar">
                                <div class="wcp-progress-fill" style="width: <?php echo esc_attr($project_progress); ?>%"></div>
                                <span class="wcp-progress-text"><?php echo esc_html($project_progress); ?>%</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($formatted_deadline) : ?>
                        <div class="wcp-project-deadline <?php echo isset($days_until_deadline) && $days_until_deadline < 0 ? 'overdue' : ($days_until_deadline <= 7 ? 'due-soon' : ''); ?>">
                            <strong><?php _e('Deadline:', 'workcity-client-project-manager'); ?></strong> 
                            <?php echo esc_html($formatted_deadline); ?>
                            <?php if (isset($days_until_deadline)) : ?>
                                <?php if ($days_until_deadline < 0) : ?>
                                    <span class="deadline-alert">(<?php echo abs($days_until_deadline); ?> days overdue)</span>
                                <?php elseif ($days_until_deadline <= 7) : ?>
                                    <span class="deadline-warning">(<?php echo $days_until_deadline; ?> days remaining)</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($project_budget) : ?>
                        <div class="wcp-project-budget">
                            <strong><?php _e('Budget:', 'workcity-client-project-manager'); ?></strong> 
                            <?php echo esc_html($currency_symbol . number_format($project_budget, 2)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (get_the_content()) : ?>
                    <div class="wcp-project-description">
                        <strong><?php _e('Description:', 'workcity-client-project-manager'); ?></strong>
                        <div class="wcp-description-content">
                            <?php echo wpautop(wp_kses_post(get_the_content())); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
        
        echo '</div>';
    } else {
        echo '<p class="wcp-no-projects">' . esc_html__('No client projects found.', 'workcity-client-project-manager') . '</p>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * Enqueue plugin styles
 */
function wcp_enqueue_styles() {
    wp_enqueue_style('wcp-style', WCP_PLUGIN_URL . 'assets/style.css', array(), '1.0.0');
}

/**
 * Enqueue admin scripts and styles
 */
function wcp_enqueue_admin_scripts($hook) {
    global $post_type;
    
    if ($post_type === 'client_project') {
        wp_enqueue_style('wcp-admin-style', WCP_PLUGIN_URL . 'assets/admin-style.css', array(), '1.0.0');
    }
}

/**
 * Add dashboard widget for project statistics
 */
function wcp_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'wcp_project_stats',
        __('Project Statistics', 'workcity-client-project-manager'),
        'wcp_dashboard_widget_content'
    );
}

/**
 * Dashboard widget content
 */
function wcp_dashboard_widget_content() {
    // Get project statistics
    $total_projects = wp_count_posts('client_project')->publish;
    
    $status_counts = array();
    $statuses = array('not_started', 'in_progress', 'completed', 'on_hold');
    
    foreach ($statuses as $status) {
        $count = get_posts(array(
            'post_type' => 'client_project',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_wcp_project_status',
                    'value' => $status,
                    'compare' => '='
                )
            ),
            'numberposts' => -1
        ));
        $status_counts[$status] = count($count);
    }
    
    // Get overdue projects
    $overdue_projects = get_posts(array(
        'post_type' => 'client_project',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_wcp_project_deadline',
                'value' => date('Y-m-d'),
                'compare' => '<'
            ),
            array(
                'key' => '_wcp_project_status',
                'value' => 'completed',
                'compare' => '!='
            )
        ),
        'numberposts' => -1
    ));
    
    $status_names = array(
        'not_started' => __('Not Started', 'workcity-client-project-manager'),
        'in_progress' => __('In Progress', 'workcity-client-project-manager'),
        'completed' => __('Completed', 'workcity-client-project-manager'),
        'on_hold' => __('On Hold', 'workcity-client-project-manager')
    );
    
    ?>
    <div class="wcp-dashboard-stats">
        <div class="wcp-stat-box">
            <h4><?php _e('Total Projects', 'workcity-client-project-manager'); ?></h4>
            <span class="wcp-stat-number"><?php echo esc_html($total_projects); ?></span>
        </div>
        
        <div class="wcp-status-breakdown">
            <h4><?php _e('Projects by Status', 'workcity-client-project-manager'); ?></h4>
            <ul>
                <?php foreach ($status_counts as $status => $count) : ?>
                    <li class="status-<?php echo esc_attr($status); ?>">
                        <span class="status-dot"></span>
                        <?php echo esc_html($status_names[$status]); ?>: 
                        <strong><?php echo esc_html($count); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <?php if (count($overdue_projects) > 0) : ?>
            <div class="wcp-overdue-alert">
                <h4 style="color: #d32f2f;"><?php _e('Overdue Projects', 'workcity-client-project-manager'); ?></h4>
                <p><?php printf(_n('%d project is overdue!', '%d projects are overdue!', count($overdue_projects), 'workcity-client-project-manager'), count($overdue_projects)); ?></p>
                <ul>
                    <?php foreach (array_slice($overdue_projects, 0, 5) as $project) : ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($project->ID); ?>">
                                <?php echo esc_html($project->post_title); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($overdue_projects) > 5) : ?>
                        <li><em><?php printf(__('...and %d more', 'workcity-client-project-manager'), count($overdue_projects) - 5); ?></em></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="wcp-quick-actions">
            <a href="<?php echo admin_url('post-new.php?post_type=client_project'); ?>" class="button button-primary">
                <?php _e('Add New Project', 'workcity-client-project-manager'); ?>
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=client_project'); ?>" class="button">
                <?php _e('View All Projects', 'workcity-client-project-manager'); ?>
            </a>
        </div>
    </div>
    
    <style>
    .wcp-dashboard-stats .wcp-stat-box {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 15px;
    }
    
    .wcp-stat-number {
        font-size: 2em;
        font-weight: bold;
        color: #0073aa;
        display: block;
    }
    
    .wcp-status-breakdown ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .wcp-status-breakdown li {
        display: flex;
        align-items: center;
        padding: 5px 0;
    }
    
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
        display: inline-block;
    }
    
    .status-not_started .status-dot { background: #666; }
    .status-in_progress .status-dot { background: #f57c00; }
    .status-completed .status-dot { background: #388e3c; }
    .status-on_hold .status-dot { background: #d32f2f; }
    
    .wcp-overdue-alert {
        background: #ffebee;
        padding: 10px;
        border-left: 4px solid #d32f2f;
        margin: 15px 0;
    }
    
    .wcp-overdue-alert ul {
        margin: 5px 0 0 0;
        padding-left: 20px;
    }
    
    .wcp-quick-actions {
        margin-top: 15px;
        text-align: center;
    }
    
    .wcp-quick-actions .button {
        margin: 0 5px;
    }
    </style>
    <?php
}
