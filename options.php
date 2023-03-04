<?php


// Settings Page: EPOptions
// Retrieving values: get_option( 'your_field_id' )
class EPOptions_Settings_Page {

  public function __construct() {
    add_action('admin_menu', array($this, 'wph_create_settings'));
    add_action('admin_init', array($this, 'wph_setup_sections'));
    add_action('admin_init', array($this, 'wph_setup_fields'));
  }

  public function wph_create_settings() {
    $page_title = 'EPT Options';
    $menu_title = 'EPT Options';
    $capability = 'manage_options';
    $slug = 'EPOptions';
    $callback = array($this, 'wph_settings_content');
    $icon = 'dashicons-translation';
    $position = 80;
    add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
  }

  public function wph_settings_content() { ?>
    <div class="wrap">
      <h1>EP Options</h1>
      <?php settings_errors(); ?>
      <form method="POST" action="options.php">
        <?php
        settings_fields('EPOptions');
        do_settings_sections('EPOptions');
        submit_button();
        ?>
      </form>
    </div> <?php
          }

          public function wph_setup_sections() {
            add_settings_section('EPOptions_section', '', array(), 'EPOptions');
          }

          public function wph_setup_fields() {
            $post_types = (array) get_post_types(['public' => true], 'objects');
            unset($post_types['attachment']);
            $options = array_combine(array_keys($post_types), array_column($post_types, 'label'));
            $fields = array(
              array(
                'section' => 'EPOptions_section',
                'label' => 'Post Types',
                'id' => 'ep_translator_post_types',
                'type' => 'checkbox',
                'choices' => $options,
                'default' => ['post', 'page']
              )
            );
            foreach ($fields as $field) {
              add_settings_field($field['id'], $field['label'], array($this, 'wph_field_callback'), 'EPOptions', $field['section'], $field);
              register_setting('EPOptions', $field['id']);
            }
          }
          public function wph_field_callback($field) {
            $value = get_option($field['id']);
            $placeholder = '';
            if (isset($field['placeholder'])) {
              $placeholder = $field['placeholder'];
            }
            switch ($field['type']) {

              case 'checkbox':
                foreach ($field['choices'] as $key => $label) {
                  $checked = '';

                  if ($value === FALSE && isset($field['default'])) {
                    $checked = in_array($key, $field['default']) ? 'checked' : '';
                  }

                  if (is_array($value)) {
                    $checked = in_array($key, array_keys($value)) ? 'checked' : '';
                  }

                  printf(
                    '<p><label><input name="%1$s[%2$s]" id="%1$s_%2$s" type="checkbox" value="on" %4$s />%3$s</label></p>',
                    $field['id'],
                    $key,
                    $label,
                    $checked
                  );
                }
                break;

              default:
                printf(
                  '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                  $field['id'],
                  $field['type'],
                  $placeholder,
                  $value
                );
            }
            if (isset($field['desc'])) {
              if ($desc = $field['desc']) {
                printf('<p class="description">%s </p>', $desc);
              }
            }
          }
        }
        new EPOptions_Settings_Page();
