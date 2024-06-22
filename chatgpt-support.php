<?php
/*
Plugin Name: ChatGPT Support
Description: A plugin to connect to ChatGPT API for support.
Version: 1.0
Author: Farshid Mahmoudi
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Add menu item for settings page
add_action('admin_menu', 'chatgpt_support_menu');
function chatgpt_support_menu() {
    add_menu_page('ChatGPT Support Settings', 'ChatGPT Support', 'manage_options', 'chatgpt-support', 'chatgpt_support_settings_page');
}

// Display the settings page
function chatgpt_support_settings_page() {
    ?>
    <div class="wrap">
        <h1>ChatGPT Support Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('chatgpt_support_options_group');
            do_settings_sections('chatgpt-support');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'chatgpt_support_settings');
function chatgpt_support_settings() {
    register_setting('chatgpt_support_options_group', 'chatgpt_support_api_key');

    add_settings_section(
        'chatgpt_support_section',
        'API Settings',
        'chatgpt_support_section_callback',
        'chatgpt-support'
    );

    add_settings_field(
        'chatgpt_support_api_key',
        'API Key',
        'chatgpt_support_api_key_callback',
        'chatgpt-support',
        'chatgpt_support_section'
    );
}

function chatgpt_support_section_callback() {
    echo 'Enter your ChatGPT API key below:';
}

function chatgpt_support_api_key_callback() {
    $api_key = get_option('chatgpt_support_api_key');
    echo '<input type="text" name="chatgpt_support_api_key" value="' . esc_attr($api_key) . '" />';
}

// Enqueue the chat icon and script
add_action('wp_enqueue_scripts', 'enqueue_chatgpt_support_scripts');
function enqueue_chatgpt_support_scripts() {
    wp_enqueue_style('chatgpt-support-style', plugins_url('chatgpt-support.css', __FILE__));
    wp_enqueue_script('chatgpt-support-script', plugins_url('chatgpt-support.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('chatgpt-support-script', 'chatgptSupport', array(
        'apiKey' => get_option('chatgpt_support_api_key'),
    ));
}

// Add chat icon HTML
add_action('wp_footer', 'add_chatgpt_support_icon');
function add_chatgpt_support_icon() {
    ?>
    <div id="chatgpt-support-icon" onclick="toggleChatWindow()">💬</div>
    <div id="chatgpt-support-window" style="display:none;">
        <div id="chatgpt-support-header">
            <h3>Chat with Support</h3>
            <span onclick="toggleChatWindow()">X</span>
        </div>
        <div id="chatgpt-support-body">
            <textarea id="chatgpt-support-query" rows="4" cols="50"></textarea><br>
            <button type="button" onclick="sendToChatGPT()">Send</button>
            <div id="chatgpt-support-response"></div>
        </div>
    </div>
    <?php
}
?>
