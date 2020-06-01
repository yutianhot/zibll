<?php

if (!function_exists('of_get_posts_meta')) {
    function of_get_posts_meta($name, $key, $default = false, $post_id = '')
    {
        global $post;
        $post_id = $post_id ? $post_id : $post->ID;
        $get_mate = get_post_meta($post->ID, $name, true);
        if (isset($get_mate[$key])) {
            return $get_mate[$key];
        }
        return $default;
    }
}

class Zib_add_meta_box
{

    function __construct($metas, $args)
    {
        $this->meta = $metas;
        $this->args = $args;
        add_action('add_meta_boxes', array($this, 'wp_enqueue'));
        add_action('add_meta_boxes', array($this, 'add_meta'));
        add_action('save_post',  array($this, 'save_meta'));
    }

    public function add_meta()
    {

        foreach ($this->meta['type'] as $type)
            add_meta_box($this->meta['id'] . '_meta_box', $this->meta['name'], array($this, 'edit_meta'), $type, 'normal', 'high');
    }

    public function wp_enqueue()
    {
        wp_enqueue_style('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/framework-posts-meta.css', array(),  Options_Framework::VERSION);
        wp_enqueue_script('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'js/posts-custom.js', array('jquery', 'wp-color-picker'), Options_Framework::VERSION);
    }

    public function edit_meta($term)
    {
        global $post;
        $output = '';
        $metas = $this->meta;
        $meta_id = $metas['id'];
        $get_mate = get_post_meta($post->ID, $meta_id, true);
        $counter = 0;
        foreach ($this->args as $meta) {
            $value_id = isset($meta['id']) ? $meta['id'] : '';
            $std = isset($meta['std']) ? $meta['std'] : '';
            $class = isset($meta['class']) ? $meta['class'] : '';
            $question = isset($meta['question']) ? $meta['question'] : '';
            $type = isset($meta['type']) ? $meta['type'] : '';
            $placeholder = isset($meta['placeholder']) ? $meta['placeholder'] : '';
            $value = '';
            $value = isset($get_mate[$value_id]) ? $get_mate[$value_id] : $std;
            $class = '';
            if (isset($meta['type'])) {
                $class .= ' option-' . $meta['type'];
            }
            if (isset($meta['class'])) {
                $class .= ' ' . $meta['class'];
            }
            $output .= '<div class="input-box ' . $class . '">' . "\n";

            $output .= '<div class="heading">' . (isset($meta['name']) ? esc_html($meta['name']) : '') . '</div>' . "\n";

            $output .= '<div class="option">' . "\n";
            //echo json_encode($meta);
            switch ($type) {

                    // Basic text input
                case 'text':
                    $output .= '<input class="of-input" name="' . esc_attr($meta_id . '[' . $value_id . ']') . '" type="text" value="' . esc_attr($value) . '"/>';
                    break;

                    // Password input
                case 'password':
                    $output .= '<input class="of-input" name="' . esc_attr($meta_id . '[' . $value_id . ']') . '" type="password" value="' . esc_attr($value) . '"/>';
                    break;

                case 'html':
                    $output .= $meta['html'];
                    break;

                case 'number':
                    $output .= '<input class="of-input" name="' . esc_attr($meta_id . '[' . $value_id . ']') . '" type="number" value="' . esc_attr($value) . '"/>';
                    break;

                case 'checkbox':
                    $output .= '<label><input class="of-checkbox" name="' . esc_attr($meta_id . '[' . $value_id . ']') . '" type="checkbox" ' . checked($value, 1, false) . '/>' . esc_html($meta['desc']) . '</label>';
                    break;
                case "upload":
                    $output .= Options_Framework_Media_Uploader::optionsframework_uploader($value_id, $value, null);

                    break;

                    // Textarea
                case 'textarea':
                    $rows = '4';

                    if (isset($meta['settings']['rows'])) {
                        $custom_rows = $meta['settings']['rows'];
                        if (is_numeric($custom_rows)) {
                            $rows = $custom_rows;
                        }
                    }

                    $value = stripslashes($value);
                    $output .= '<textarea class="of-input" name="' . esc_attr($meta_id . '[' . $value_id . ']')  . '" rows="' . $rows . '"' . $placeholder . '>' . esc_textarea($value) . '</textarea>';
                    break;

                    // Select Box
                case 'select':
                    $output .= '<select class="of-select" name="' . esc_attr($meta_id . '[' . $value_id . ']')  . '">';

                    foreach ($meta['options'] as $key => $option) {
                        $output .= '<option' . selected($value, $key, false) . ' value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
                    }
                    $output .= '</select>';
                    break;

                    // Radio Box
                case "radio":
                    foreach ($meta['options'] as $key => $option) {
                        $output .= '<label><input class="of-radio" type="radio" name="' . esc_attr($meta_id . '[' . $value_id . ']') . '" value="' . esc_attr($key) . '" ' . checked($value, $key, false) . ' />' . esc_html($option) . '</label>';
                    }
                    break;
            }

            if (!empty($meta['desc']) && $type != 'checkbox') {
                $desc = esc_html($meta['desc']);

                $output .= '<div class="desc">' . $desc .  '</div>' . "\n";
            }

            if ($question) {
                $output .= '<span class="edit-question dashicons dashicons-editor-help"></span><div class="question">' . $question . '</div>' . "\n";
            }

            $output .= '</div>' . "\n";
            $output .= '</div>' . "\n";
        }

        echo '<div class="framework-posts">';
        echo $output;
        echo '</div>';
        // echo  json_encode( $get_mate);
        //  echo json_encode( $this->args);
    }

    public function save_meta($post_id)
    {

        $metas = $this->meta;
        $args = $this->args;

        $meta_id = $metas['id'];
        if (!current_user_can('edit_posts', $post_id)) return;
        if (isset($_POST[$meta_id])) {

            $clean = array();
            $input = $_POST[$meta_id];

            foreach ($args as $option) {
                if (!isset($option['id'])) {
                    continue;
                }

                if (!isset($option['type'])) {
                    continue;
                }

                $id = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['id']));

                // Set checkbox to false if it wasn't sent in the $_POST
                if ('checkbox' == $option['type'] && !isset($input[$id])) {
                    $input[$id] = $input[$id];
                }

                // Set each item in the multicheck to false if it wasn't sent in the $_POST
                if ('multicheck' == $option['type'] && !isset($input[$id])) {
                    foreach ($option['options'] as $key => $value) {
                        $input[$id][$key] = false;
                    }
                }

                // For a value to be submitted to database it must pass through a sanitization filter
                $clean[$id] = apply_filters('of_sanitize_' . $option['type'], $input[$id], $option);
            }

            update_post_meta($post_id, $meta_id, $clean);
        }
    }
}
