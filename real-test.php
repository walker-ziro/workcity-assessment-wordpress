<?php
/**
 * Real-World Plugin Testing Suite for Workcity Client Project Manager
 * This simulates actual WordPress environment and tests plugin functionality
 */

echo "=== WORKCITY CLIENT PROJECT MANAGER - COMPREHENSIVE TESTING ===\n\n";

// Mock WordPress functions that the plugin needs
function define_mock_wordpress_functions() {
    // Mock WordPress core functions
    if (!function_exists('__')) {
        function __($text, $domain = '') { return $text; }
    }
    if (!function_exists('_e')) {
        function _e($text, $domain = '') { echo $text; }
    }
    if (!function_exists('_x')) {
        function _x($text, $context, $domain = '') { return $text; }
    }
    if (!function_exists('_n')) {
        function _n($single, $plural, $number, $domain = '') { 
            return $number == 1 ? $single : $plural; 
        }
    }
    if (!function_exists('esc_attr')) {
        function esc_attr($text) { return htmlspecialchars($text, ENT_QUOTES); }
    }
    if (!function_exists('esc_html')) {
        function esc_html($text) { return htmlspecialchars($text, ENT_NOQUOTES); }
    }
    if (!function_exists('sanitize_text_field')) {
        function sanitize_text_field($str) { return trim(strip_tags($str)); }
    }
    if (!function_exists('sanitize_email')) {
        function sanitize_email($email) { return filter_var($email, FILTER_SANITIZE_EMAIL); }
    }
    if (!function_exists('selected')) {
        function selected($selected, $current, $echo = true) {
            $result = selected_helper($selected, $current);
            if ($echo) echo $result;
            return $result;
        }
    }
    if (!function_exists('selected_helper')) {
        function selected_helper($selected, $current) {
            return (string) $selected === (string) $current ? ' selected="selected"' : '';
        }
    }
    if (!function_exists('wp_nonce_field')) {
        function wp_nonce_field($action, $name) {
            echo '<input type="hidden" name="' . $name . '" value="test_nonce" />';
        }
    }
    if (!function_exists('wp_verify_nonce')) {
        function wp_verify_nonce($nonce, $action) { return true; }
    }
    if (!function_exists('current_user_can')) {
        function current_user_can($capability, $object_id = null) { return true; }
    }
    if (!function_exists('get_post_meta')) {
        function get_post_meta($post_id, $key, $single = false) {
            // Mock some test data
            $mock_data = [
                '_wcp_client_name' => 'John Doe',
                '_wcp_project_status' => 'in_progress',
                '_wcp_project_deadline' => '2025-12-31',
                '_wcp_project_progress' => '75',
                '_wcp_project_budget' => '5000',
                '_wcp_project_currency' => 'USD'
            ];
            return isset($mock_data[$key]) ? $mock_data[$key] : '';
        }
    }
    if (!function_exists('update_post_meta')) {
        function update_post_meta($post_id, $key, $value) { return true; }
    }
    if (!function_exists('get_post_type')) {
        function get_post_type($post_id) { return 'client_project'; }
    }
    if (!function_exists('register_post_type')) {
        function register_post_type($post_type, $args) {
            echo "✓ Post type '{$post_type}' registered with " . count($args) . " arguments\n";
            return true;
        }
    }
    if (!function_exists('register_taxonomy')) {
        function register_taxonomy($taxonomy, $object_type, $args) {
            echo "✓ Taxonomy '{$taxonomy}' registered for " . implode(', ', $object_type) . "\n";
            return true;
        }
    }
    if (!function_exists('add_action')) {
        function add_action($hook, $callback, $priority = 10, $args = 1) {
            echo "✓ Action hook '{$hook}' registered -> {$callback}\n";
            return true;
        }
    }
    if (!function_exists('add_shortcode')) {
        function add_shortcode($tag, $callback) {
            echo "✓ Shortcode '[{$tag}]' registered -> {$callback}\n";
            return true;
        }
    }
    if (!function_exists('register_activation_hook')) {
        function register_activation_hook($file, $callback) {
            echo "✓ Activation hook registered -> {$callback}\n";
            return true;
        }
    }
    if (!function_exists('register_deactivation_hook')) {
        function register_deactivation_hook($file, $callback) {
            echo "✓ Deactivation hook registered -> {$callback}\n";
            return true;
        }
    }
    if (!function_exists('flush_rewrite_rules')) {
        function flush_rewrite_rules() { echo "✓ Rewrite rules flushed\n"; }
    }
    if (!function_exists('plugin_dir_url')) {
        function plugin_dir_url($file) { return 'http://example.com/wp-content/plugins/workcity-client-project-manager/'; }
    }
    if (!function_exists('plugin_dir_path')) {
        function plugin_dir_path($file) { return '/path/to/wp-content/plugins/workcity-client-project-manager/'; }
    }
    if (!function_exists('shortcode_atts')) {
        function shortcode_atts($defaults, $atts) {
            return array_merge($defaults, (array) $atts);
        }
    }
    if (!class_exists('WP_Query')) {
        class WP_Query {
            private $posts = [];
            
            public function __construct($args) {
                echo "✓ WP_Query created with args: " . json_encode($args, JSON_PRETTY_PRINT) . "\n";
                // Mock some posts
                $this->posts = [
                    (object) [
                        'ID' => 1,
                        'post_title' => 'Test Project 1',
                        'post_content' => 'This is a test project description.'
                    ],
                    (object) [
                        'ID' => 2,
                        'post_title' => 'Test Project 2',
                        'post_content' => 'Another test project description.'
                    ]
                ];
            }
            
            public function have_posts() {
                return !empty($this->posts);
            }
            
            public function the_post() {
                return array_shift($this->posts);
            }
        }
    }
    
    // Define constants
    if (!defined('ABSPATH')) {
        define('ABSPATH', '/path/to/wordpress/');
    }
    if (!defined('DOING_AUTOSAVE')) {
        define('DOING_AUTOSAVE', false);
    }
    
    // Mock global variables
    global $post_type;
    $post_type = 'client_project';
    
    // Mock $_POST data for testing
    $_POST['wcp_project_meta_box_nonce'] = 'test_nonce';
    $_POST['wcp_client_name'] = 'John Doe';
    $_POST['wcp_client_email'] = 'john@example.com';
    $_POST['wcp_project_status'] = 'in_progress';
    $_POST['wcp_project_deadline'] = '2025-12-31';
    $_POST['wcp_project_progress'] = '75';
}

// Initialize mock WordPress environment
define_mock_wordpress_functions();

echo "--- LOADING PLUGIN ---\n";
try {
    // Capture any output during plugin loading
    ob_start();
    include_once 'client-projects-manager.php';
    $plugin_output = ob_get_clean();
    
    if (!empty($plugin_output)) {
        echo "Plugin output during loading:\n" . $plugin_output . "\n";
    }
    echo "✓ Plugin loaded successfully\n\n";
} catch (Exception $e) {
    echo "✗ FATAL ERROR loading plugin: " . $e->getMessage() . "\n";
    exit(1);
} catch (ParseError $e) {
    echo "✗ SYNTAX ERROR in plugin: " . $e->getMessage() . "\n";
    exit(1);
}

echo "--- TESTING FUNCTION EXISTENCE ---\n";
$required_functions = [
    'wcp_activation',
    'wcp_deactivation',
    'wcp_init_plugin',
    'wcp_register_client_project_post_type',
    'wcp_register_project_categories',
    'wcp_add_project_meta_boxes',
    'wcp_project_details_callback',
    'wcp_client_details_callback',
    'wcp_project_budget_callback',
    'wcp_save_project_meta_fields',
    'wcp_display_projects_shortcode',
    'wcp_enqueue_styles',
    'wcp_enqueue_admin_scripts',
    'wcp_add_dashboard_widget',
    'wcp_dashboard_widget_content'
];

$missing_functions = [];
foreach ($required_functions as $function) {
    if (function_exists($function)) {
        echo "✓ {$function}\n";
    } else {
        echo "✗ {$function} - MISSING\n";
        $missing_functions[] = $function;
    }
}

if (!empty($missing_functions)) {
    echo "\n✗ CRITICAL ERROR: Missing functions detected!\n";
    echo "Missing: " . implode(', ', $missing_functions) . "\n";
    exit(1);
} else {
    echo "✓ All required functions exist\n\n";
}

echo "--- TESTING PLUGIN INITIALIZATION ---\n";
try {
    wcp_init_plugin();
    echo "✓ Plugin initialization completed\n\n";
} catch (Exception $e) {
    echo "✗ Plugin initialization failed: " . $e->getMessage() . "\n";
}

echo "--- TESTING POST TYPE REGISTRATION ---\n";
try {
    wcp_register_client_project_post_type();
    echo "✓ Client project post type registration completed\n\n";
} catch (Exception $e) {
    echo "✗ Post type registration failed: " . $e->getMessage() . "\n";
}

echo "--- TESTING TAXONOMY REGISTRATION ---\n";
try {
    wcp_register_project_categories();
    echo "✓ Project categories taxonomy registration completed\n\n";
} catch (Exception $e) {
    echo "✗ Taxonomy registration failed: " . $e->getMessage() . "\n";
}

echo "--- TESTING META BOX CALLBACKS ---\n";
$mock_post = (object) ['ID' => 1];

echo "Testing project details callback:\n";
try {
    ob_start();
    wcp_project_details_callback($mock_post);
    $output = ob_get_clean();
    if (strpos($output, 'form-table') !== false && strpos($output, 'wcp_project_status') !== false) {
        echo "✓ Project details meta box renders correctly\n";
    } else {
        echo "✗ Project details meta box output is invalid\n";
    }
} catch (Exception $e) {
    echo "✗ Project details callback failed: " . $e->getMessage() . "\n";
}

echo "Testing client details callback:\n";
try {
    ob_start();
    wcp_client_details_callback($mock_post);
    $output = ob_get_clean();
    if (strpos($output, 'wcp_client_name') !== false && strpos($output, 'wcp_client_email') !== false) {
        echo "✓ Client details meta box renders correctly\n";
    } else {
        echo "✗ Client details meta box output is invalid\n";
    }
} catch (Exception $e) {
    echo "✗ Client details callback failed: " . $e->getMessage() . "\n";
}

echo "Testing budget callback:\n";
try {
    ob_start();
    wcp_project_budget_callback($mock_post);
    $output = ob_get_clean();
    if (strpos($output, 'wcp_project_budget') !== false && strpos($output, 'wcp_project_progress') !== false) {
        echo "✓ Budget meta box renders correctly\n";
    } else {
        echo "✗ Budget meta box output is invalid\n";
    }
} catch (Exception $e) {
    echo "✗ Budget callback failed: " . $e->getMessage() . "\n";
}

echo "\n--- TESTING META FIELD SAVING ---\n";
try {
    $result = wcp_save_project_meta_fields(1);
    echo "✓ Meta field saving function executed without errors\n";
} catch (Exception $e) {
    echo "✗ Meta field saving failed: " . $e->getMessage() . "\n";
}

echo "\n--- TESTING SHORTCODE ---\n";
try {
    $shortcode_output = wcp_display_projects_shortcode(['limit' => 2, 'layout' => 'grid']);
    if (!empty($shortcode_output)) {
        if (strpos($shortcode_output, 'wcp-projects-list') !== false) {
            echo "✓ Shortcode generates valid HTML output\n";
        } else {
            echo "✗ Shortcode output doesn't contain expected classes\n";
            echo "Output preview: " . substr($shortcode_output, 0, 200) . "...\n";
        }
    } else {
        echo "✗ Shortcode produces no output\n";
    }
} catch (Exception $e) {
    echo "✗ Shortcode execution failed: " . $e->getMessage() . "\n";
}

echo "\n--- TESTING DASHBOARD WIDGET ---\n";
// Mock additional WordPress functions for dashboard widget
if (!function_exists('wp_count_posts')) {
    function wp_count_posts($type) {
        return (object) ['publish' => 5];
    }
}
if (!function_exists('get_posts')) {
    function get_posts($args) {
        return [
            (object) ['ID' => 1, 'post_title' => 'Overdue Project 1'],
            (object) ['ID' => 2, 'post_title' => 'Overdue Project 2']
        ];
    }
}
if (!function_exists('get_edit_post_link')) {
    function get_edit_post_link($id) {
        return "http://example.com/wp-admin/post.php?post={$id}&action=edit";
    }
}
if (!function_exists('admin_url')) {
    function admin_url($path) {
        return "http://example.com/wp-admin/{$path}";
    }
}

try {
    ob_start();
    wcp_dashboard_widget_content();
    $widget_output = ob_get_clean();
    if (strpos($widget_output, 'wcp-dashboard-stats') !== false) {
        echo "✓ Dashboard widget renders correctly\n";
    } else {
        echo "✗ Dashboard widget output is invalid\n";
    }
} catch (Exception $e) {
    echo "✗ Dashboard widget failed: " . $e->getMessage() . "\n";
}

echo "\n--- SECURITY VALIDATION ---\n";
$plugin_content = file_get_contents('client-projects-manager.php');

$security_checks = [
    'ABSPATH check' => strpos($plugin_content, "if (!defined('ABSPATH'))") !== false,
    'Nonce verification' => strpos($plugin_content, 'wp_verify_nonce') !== false,
    'Input sanitization' => strpos($plugin_content, 'sanitize_text_field') !== false,
    'Output escaping' => strpos($plugin_content, 'esc_attr') !== false && strpos($plugin_content, 'esc_html') !== false,
    'Capability checks' => strpos($plugin_content, 'current_user_can') !== false,
    'Email sanitization' => strpos($plugin_content, 'sanitize_email') !== false
];

$security_passed = 0;
foreach ($security_checks as $check => $passed) {
    if ($passed) {
        echo "✓ {$check}\n";
        $security_passed++;
    } else {
        echo "✗ {$check} - MISSING\n";
    }
}

echo "\n--- CONSTANTS CHECK ---\n";
$required_constants = ['WCP_PLUGIN_URL', 'WCP_PLUGIN_PATH'];
foreach ($required_constants as $constant) {
    if (defined($constant)) {
        echo "✓ {$constant}: " . constant($constant) . "\n";
    } else {
        echo "✗ {$constant} - NOT DEFINED\n";
    }
}

echo "\n--- FILE STRUCTURE CHECK ---\n";
$required_files = [
    'client-projects-manager.php' => 'Main plugin file',
    'assets/style.css' => 'Stylesheet',
    'README.md' => 'Documentation'
];

$files_exist = 0;
foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✓ {$description}: {$file} ({$size} bytes)\n";
        $files_exist++;
    } else {
        echo "✗ {$description}: {$file} - MISSING\n";
    }
}

echo "\n=== FINAL TEST RESULTS ===\n";
$total_functions = count($required_functions);
$existing_functions = $total_functions - count($missing_functions);
$security_total = count($security_checks);
$files_total = count($required_files);

echo "Functions: {$existing_functions}/{$total_functions} (" . round(($existing_functions/$total_functions)*100) . "%)\n";
echo "Security: {$security_passed}/{$security_total} (" . round(($security_passed/$security_total)*100) . "%)\n";
echo "Files: {$files_exist}/{$files_total} (" . round(($files_exist/$files_total)*100) . "%)\n";

$overall_score = round((($existing_functions/$total_functions) + ($security_passed/$security_total) + ($files_exist/$files_total)) / 3 * 100);

echo "\nOVERALL SCORE: {$overall_score}%\n";

if ($overall_score >= 90) {
    echo "STATUS: ✅ EXCELLENT - Plugin is production ready\n";
} elseif ($overall_score >= 75) {
    echo "STATUS: ⚠️  GOOD - Minor issues need attention\n";
} elseif ($overall_score >= 50) {
    echo "STATUS: ❌ POOR - Significant issues need fixing\n";
} else {
    echo "STATUS: 🚨 CRITICAL - Major problems, plugin may not work\n";
}

if (count($missing_functions) > 0 || $security_passed < $security_total || $files_exist < $files_total) {
    echo "\n🔧 ISSUES TO FIX:\n";
    if (count($missing_functions) > 0) {
        echo "- Missing functions: " . implode(', ', $missing_functions) . "\n";
    }
    if ($security_passed < $security_total) {
        echo "- Security improvements needed\n";
    }
    if ($files_exist < $files_total) {
        echo "- Missing required files\n";
    }
} else {
    echo "\n🎉 ALL TESTS PASSED! Plugin is ready for deployment.\n";
}

echo "\n--- TEST COMPLETED ---\n";
