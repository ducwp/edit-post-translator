<?php

/**
 * Plugin Name: Edit Post Translator
 * Description: Awesome Plugin for WordPress
 * Plugin URI:  http://layoutup.com/awesome-wp-plugin
 * Version:     1.0.0
 * Author:      Duc Nguyen [0936 770 119]
 * Author URI:  http://layoutup.com
 * Text Domain: wpgraby
 */

require_once __DIR__ . '/options.php';

function ep_check_types() {
  global $typenow;
  $post_types = get_option('ep_translator_post_types', []);
  if (is_array($post_types) && in_array($typenow, array_keys($post_types))) return true;

  return false;
}

function ep_translator_box($post) {
  if (!ep_check_types()) return;

  echo '<div class="postbox ep-translator-box">
        <div class="postbox-header"><h2 class="hndle ui-sortable-handle">' . __('Post Translator', 'wpgraby') . '</h2></div>
        <div class="inside">
          <div id="google_translate_element"></div>
          <p>Plugin by <b><a target="_blank" href="https://wpgraby.com">WPGRABY.com</a></b></p>
        </div>
      </div>';
}
add_action('submitpost_box', 'ep_translator_box');

function ep_translator_scripts() {
  if (!ep_check_types()) return;
  
  wp_enqueue_style('ep-translator-style', plugins_url('/css/style.css', __FILE__));
  wp_enqueue_script('ep-translator-script', plugins_url('/js/script.js', __FILE__), ['jquery'], '1.0.0', true);
  $params = array(
    'pageLanguage' =>  get_option('WPLANG', 'en')
  );
  wp_localize_script('ep-translator-script', 'ep_i10n', $params);
  wp_enqueue_script('ep-translator-google', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', [], false, true);
}
add_action('admin_enqueue_scripts', 'ep_translator_scripts');

function ep_add_body_classes($classes) {
  return "$classes notranslate";
}
add_filter('admin_body_class', 'ep_add_body_classes');
