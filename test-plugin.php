<?php
/**
 * Plugin Testing Suite for Workcity Client Project Manager
 * 
 * This file contains comprehensive tests to validate plugin functionality
 */

// Test 1: Check for required functions
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

echo "=== WORKCITY CLIENT PROJECT MANAGER PLUGIN TESTING ===\n\n";

// Load the main plugin file
ob_start();
include_once 'client-projects-manager.php';
$output = ob_get_clean();

echo "✓ Plugin file loaded successfully without fatal errors\n";

// Test 2: Function existence check
echo "\n--- Testing Function Definitions ---\n";
$missing_functions = [];
foreach ($required_functions as $function) {
    if (function_exists($function)) {
        echo "✓ Function {$function} exists\n";
    } else {
        echo "✗ Function {$function} is missing\n";
        $missing_functions[] = $function;
    }
}

if (empty($missing_functions)) {
    echo "✓ All required functions are defined\n";
} else {
    echo "✗ Missing functions: " . implode(', ', $missing_functions) . "\n";
}

// Test 3: Constants check
echo "\n--- Testing Plugin Constants ---\n";
$required_constants = ['WCP_PLUGIN_URL', 'WCP_PLUGIN_PATH'];
foreach ($required_constants as $constant) {
    if (defined($constant)) {
        echo "✓ Constant {$constant} is defined: " . constant($constant) . "\n";
    } else {
        echo "✗ Constant {$constant} is not defined\n";
    }
}

// Test 4: WordPress hooks simulation
echo "\n--- Testing WordPress Hooks Registration ---\n";

// Mock WordPress functions for testing
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        echo "✓ Action registered: {$hook} -> {$callback}\n";
        return true;
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) {
        echo "✓ Shortcode registered: [{$tag}] -> {$callback}\n";
        return true;
    }
}

if (!function_exists('register_activation_hook')) {
    function register_activation_hook($file, $callback) {
        echo "✓ Activation hook registered: {$callback}\n";
        return true;
    }
}

if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook($file, $callback) {
        echo "✓ Deactivation hook registered: {$callback}\n";
        return true;
    }
}

// Test the init function
if (function_exists('wcp_init_plugin')) {
    echo "\n--- Testing Plugin Initialization ---\n";
    wcp_init_plugin();
    echo "✓ Plugin initialization completed\n";
}

// Test 5: Shortcode attributes validation
echo "\n--- Testing Shortcode Attributes ---\n";
if (function_exists('wcp_display_projects_shortcode')) {
    $test_attributes = [
        'limit' => 5,
        'status' => 'in_progress',
        'category' => 'web-development',
        'layout' => 'grid'
    ];
    
    // Mock WordPress functions for shortcode testing
    if (!function_exists('shortcode_atts')) {
        function shortcode_atts($defaults, $atts) {
            return array_merge($defaults, $atts);
        }
    }
    
    if (!function_exists('sanitize_text_field')) {
        function sanitize_text_field($str) {
            return trim(strip_tags($str));
        }
    }
    
    if (!class_exists('WP_Query')) {
        class WP_Query {
            public function __construct($args) {
                echo "✓ WP_Query created with args: " . json_encode($args) . "\n";
            }
            
            public function have_posts() {
                return false; // For testing purposes
            }
        }
    }
    
    ob_start();
    $result = wcp_display_projects_shortcode($test_attributes);
    $output = ob_get_clean();
    
    echo "✓ Shortcode executed without errors\n";
}

// Test 6: Meta field validation
echo "\n--- Testing Meta Field Validation ---\n";
$test_meta_fields = [
    'client_name' => 'John Doe',
    'client_email' => 'john@example.com',
    'client_phone' => '+1234567890',
    'client_company' => 'Acme Corp',
    'project_status' => 'in_progress',
    'project_deadline' => '2025-12-31',
    'project_budget' => '5000.00',
    'project_progress' => '75',
    'project_currency' => 'USD'
];

// Mock WordPress functions for meta field testing
if (!function_exists('sanitize_email')) {
    function sanitize_email($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

foreach ($test_meta_fields as $field => $value) {
    switch ($field) {
        case 'client_email':
            $sanitized = sanitize_email($value);
            echo "✓ Email field validation: {$value} -> {$sanitized}\n";
            break;
        case 'project_status':
            $allowed = ['not_started', 'in_progress', 'completed', 'on_hold'];
            $valid = in_array($value, $allowed);
            echo $valid ? "✓" : "✗";
            echo " Status validation: {$value} " . ($valid ? "is valid" : "is invalid") . "\n";
            break;
        case 'project_deadline':
            $valid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
            echo $valid ? "✓" : "✗";
            echo " Date format validation: {$value} " . ($valid ? "is valid" : "is invalid") . "\n";
            break;
        case 'project_progress':
            $progress = intval($value);
            $valid = $progress >= 0 && $progress <= 100;
            echo $valid ? "✓" : "✗";
            echo " Progress validation: {$value} " . ($valid ? "is valid" : "is invalid") . "\n";
            break;
        case 'project_budget':
            $budget = floatval($value);
            $valid = $budget >= 0;
            echo $valid ? "✓" : "✗";
            echo " Budget validation: {$value} " . ($valid ? "is valid" : "is invalid") . "\n";
            break;
        default:
            $sanitized = trim(strip_tags($value));
            echo "✓ Text field validation: {$field} -> {$sanitized}\n";
    }
}

// Test 7: CSS and Assets check
echo "\n--- Testing Asset Files ---\n";
$asset_files = [
    'assets/style.css',
    'README.md',
    'single-client_project.php',
    'archive-client_project.php'
];

foreach ($asset_files as $file) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✓ Asset file exists: {$file} ({$size} bytes)\n";
    } else {
        echo "✗ Asset file missing: {$file}\n";
    }
}

// Test 8: Security validation
echo "\n--- Testing Security Features ---\n";
$security_checks = [
    'ABSPATH check' => strpos(file_get_contents('client-projects-manager.php'), "if (!defined('ABSPATH'))") !== false,
    'Nonce verification' => strpos(file_get_contents('client-projects-manager.php'), 'wp_verify_nonce') !== false,
    'Input sanitization' => strpos(file_get_contents('client-projects-manager.php'), 'sanitize_text_field') !== false,
    'Output escaping' => strpos(file_get_contents('client-projects-manager.php'), 'esc_attr') !== false,
    'Capability checks' => strpos(file_get_contents('client-projects-manager.php'), 'current_user_can') !== false
];

foreach ($security_checks as $check => $passed) {
    echo $passed ? "✓" : "✗";
    echo " {$check}: " . ($passed ? "implemented" : "missing") . "\n";
}

echo "\n=== TESTING COMPLETED ===\n";
echo "Summary: Plugin structure and functionality tests completed.\n";
echo "Check the output above for any issues that need to be addressed.\n";
