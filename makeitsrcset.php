<?php
/*
Plugin Name: Make it Srcset
Plugin URI: http://note-to-helf.com/wordpress-plugin-make-it-srcset/
Description: Handle responsive images with the srcset-attribute.
Author: Samuel Brynolf
Author URI: http://note-to-helf.com
Version: 2.0
*/

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)){
    die('Invalid URL');
}

class makeitSrcset {
    public function __construct(){
        add_action('plugins_loaded', array(&$this, 'mis_constants'), 1);
        add_action('plugins_loaded', array(&$this, 'mis_includes'), 2);
        add_action('plugins_loaded', array(&$this, 'mis_scripts'),3);
    }

    public function mis_constants(){
        define('mis_DIR', plugin_dir_path( __FILE__ ));
        define('mis_INCLUDES', mis_DIR . trailingslashit('mis_includes'));
    }

    public function mis_includes(){
        require_once(mis_INCLUDES . 'mis_settings.php');
        require_once(mis_INCLUDES . 'mis_functions.php');
    }

    public function mis_scripts(){

        if (mis_get_option_boolean('mis_picturefill') || mis_get_option_boolean('mis_lazyload')) {
            add_action('wp_enqueue_scripts', 'mis_enqueue_scripts');
        }
    }
}

new makeitSrcset();