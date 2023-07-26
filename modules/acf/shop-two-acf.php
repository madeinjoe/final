<?php
defined('ABSPATH') || die('Direct access not allowed');

class ShopTwoACF
{
    public function __construct()
    {
        // $this->shopTwoOptionPage();
        self::shopTwoOptionPage();
    }

    private static function shopTwoOptionPage()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title'    => 'Shop Two Settings',
                'menu_title'    => 'Shop Two Settings',
                'menu_slug'     => 'shop-two-settings',
                'capability'    => 'edit_posts',
                'redirect'      => false
            ]);
        }
    }
}

// Initialize
new ShopTwoACF();
