<?php
/**
 * Plugin Name: Workcity Client Project Manager
 * Description: A plugin to manage client projects with custom post type, meta fields, and shortcode display.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: workcity-client-project-manager
 */

// Prevent direct access
if (!defined('ABSPATH// Activation hook to flush rewrite rules
register_activation_hook(__FILE__, 'wcp_activation');
function wcp_activation() {
    // Register post type
    wcp_register_client_project_post_type();
    
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
    
    // Add meta boxes
    add_action('add_meta_boxes', 'wcp_add_project_meta_boxes');
    
    // Save meta fields
    add_action('save_post', 'wcp_save_project_meta_fields');
    
    // Register shortcode
    add_shortcode('display_projects', 'wcp_display_projects_shortcode');
    
    // Enqueue styles
    add_action('wp_enqueue_scripts', 'wcp_enqueue_styles');
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
 * Add meta boxes for Project Details
 * Creates a meta box with custom fields for client projects
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
}

/**
 * Meta box callback function for Project Details
 * Displays the custom fields form in the admin
 */
function wcp_project_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('wcp_project_meta_box', 'wcp_project_meta_box_nonce');
    
    // Get current values
    $client_name = get_post_meta($post->ID, '_wcp_client_name', true);
    $project_status = get_post_meta($post->ID, '_wcp_project_status', true);
    $project_deadline = get_post_meta($post->ID, '_wcp_project_deadline', true);
    
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
}

/**
 * Shortcode function to display all published client projects
 * Usage: [display_projects]
 */
function wcp_display_projects_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(array(
        'limit' => -1, // Show all projects by default
        'order' => 'DESC',
        'orderby' => 'date'
    ), $atts);
    
    // Query for client projects
    $args = array(
        'post_type' => 'client_project',
        'posts_per_page' => intval($atts['limit']),
        'post_status' => 'publish',
        'order' => sanitize_text_field($atts['order']),
        'orderby' => sanitize_text_field($atts['orderby'])
    );
    
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="wcp-projects-list">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            // Get meta fields
            $client_name = get_post_meta(get_the_ID(), '_wcp_client_name', true);
            $project_status = get_post_meta(get_the_ID(), '_wcp_project_status', true);
            $project_deadline = get_post_meta(get_the_ID(), '_wcp_project_deadline', true);
            
            // Format deadline
            $formatted_deadline = '';
            if ($project_deadline) {
                $formatted_deadline = date('F j, Y', strtotime($project_deadline));
            }
            
            // Status display names
            $status_names = array(
                'not_started' => __('Not Started', 'workcity-client-project-manager'),
                'in_progress' => __('In Progress', 'workcity-client-project-manager'),
                'completed' => __('Completed', 'workcity-client-project-manager'),
                'on_hold' => __('On Hold', 'workcity-client-project-manager')
            );
            
            $status_display = isset($status_names[$project_status]) ? $status_names[$project_status] : ucfirst(str_replace('_', ' ', $project_status));
            
            ?>
            <div class="wcp-project-item status-<?php echo esc_attr($project_status); ?>">
                <h3 class="wcp-project-title"><?php echo esc_html(get_the_title()); ?></h3>
                
                <div class="wcp-project-meta">
                    <?php if ($client_name) : ?>
                        <p class="wcp-client-name">
                            <strong><?php _e('Client Name:', 'workcity-client-project-manager'); ?></strong> 
                            <?php echo esc_html($client_name); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($project_status) : ?>
                        <p class="wcp-project-status">
                            <strong><?php _e('Status:', 'workcity-client-project-manager'); ?></strong> 
                            <span class="wcp-status-badge status-<?php echo esc_attr($project_status); ?>">
                                <?php echo esc_html($status_display); ?>
                            </span>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($formatted_deadline) : ?>
                        <p class="wcp-project-deadline">
                            <strong><?php _e('Deadline:', 'workcity-client-project-manager'); ?></strong> 
                            <?php echo esc_html($formatted_deadline); ?>
                        </p>
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

// Initialize the plugin
new ClientProjectsManager();

// Activation hook to flush rewrite rules
register_activation_hook(__FILE__, 'cpm_activation');
function cpm_activation() {
    // Create an instance to register post type
    $cpm = new ClientProjectsManager();
    $cpm->register_post_type();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'cpm_deactivation');
function cpm_deactivation() {
    flush_rewrite_rules();
}
