<?php
defined('ABSPATH') || die("Direct access not allowed");

class ProductMenu {
    public function __construct () {
        // add_action('admin_menu', [$this, 'productDiscountSubMenu']);
    }

    public function productDiscountSubMenu () {
        add_submenu_page(
            $parent_slug = 'product',
            $page_title = 'Discount',
            $menu_title = 'Discount',
            $capability = 'manage_options',
            $menu_slug = 'shop-setting',
            $callback = [$this, 'shopSettingRenderPage'],
            $position = 3
        );
    }
}
