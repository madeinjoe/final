<?php

/**
 * Enqueue custom scripts and styles.
 */

namespace MI_Theme\setup;

defined('ABSPATH') || die("Can't access directly");

class Enqueue
{
    public function __construct()
    {
        add_action("wp_enqueue_scripts", [$this, "frontEnd"]);
        add_filter('script_loader_tag', [$this, 'mind_defer_scripts'], 10, 3);
    }

    function frontEnd()
    {
        wp_enqueue_style(
            "frontend_style",
            get_stylesheet_directory_uri() . "/assets/dist/css/style.min.css",
            [],
            filemtime(get_template_directory() . "/assets/dist/css/style.min.css")
        );

        wp_enqueue_script(
            "vendors_script",
            get_template_directory_uri() . "/assets/dist/js/vendors.min.js",
            [],
            filemtime(get_template_directory() . "/assets/dist/js/vendors.min.js"),
            true
        );

        wp_enqueue_script(
            "frontend_script",
            get_template_directory_uri() . "/assets/dist/js/script.min.js",
            ["jquery"],
            filemtime(get_template_directory() . "/assets/dist/js/script.min.js"),
            true
        );

        /**
         * Set-up localized data for ajax request
         */
        $customRegistration = [
            "action" => "registration_handle",
            "nonce" => wp_create_nonce("_custom_registration")
        ];

        $customLogin = [
            'action' => 'login_handle',
            'nonce' => wp_create_nonce('_custom_login')
        ];

        $contactData = [
            'action' => 'contact_message_handle',
            'nonce' => wp_create_nonce('_contact_nonce')
        ];

        $cartData = [
            'action' => 'custom_woocommerce_add_to_cart',
            'nonce' => wp_create_nonce('_custom_meta_cart')
        ];

        /**
         * enqueue Example Ajax
         */
        wp_localize_script(
            'frontend_script', // Ajax Name
            'parameters', // Object name parameter
            [
                'url_admin_ajax'       => admin_url('admin-ajax.php'),
                'ajax_custom_registration' => $customRegistration,
                'ajax_custom_login' => $customLogin,
                'ajax_contact_message' => $contactData,
                'ajax_add_to_cart' => $cartData
            ]
        );
    }

    public function mind_defer_scripts($tag, $handle, $src)
    {
        $defer = array(
            'vendors_script'
        );

        if (in_array($handle, $defer)) {
            return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
        }

        return $tag;
    }
}

/*
 * initialize
 * */
new Enqueue();
