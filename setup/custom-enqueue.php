<?php

namespace Custom\Setup;

defined('ABSPATH') || die('Direct access not allowed');
define('ASSETSURI', THEME_URL . '/assets');

class CustomEnqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'initEnqueueScripts']);
    }

    public function initEnqueueScripts()
    {
        self::fontawesomeCDN();
        self::tailwindCSS();
    }

    protected static function fontawesomeCDN()
    {
        wp_register_style('fontawesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        wp_style_add_data('fontawesome-cdn', 'integrity', 'sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==');
        wp_style_add_data('fontawesome-cdn', 'integrity', 'sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==');
        wp_style_add_data('fontawesome-cdn', 'crossorigin', 'anonymous');
        wp_style_add_data('fontawesome-cdn', 'referrerpolicy', 'no-referrer');
        wp_enqueue_style('fontawesome-cdn');
    }

    protected static function tailwindCSS()
    {
        wp_enqueue_style('tailwind-css', ASSETSURI . '/dist/css/tailwind.min.css');
    }
}

// Initiate
new CustomEnqueue();
