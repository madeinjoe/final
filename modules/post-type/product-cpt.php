<?php
defined('ABSPATH') || die("Direct access not allowed");

class ProductCPT extends RegisterCPT {
    public function __construct() {
        add_action('init', [$this, 'productBrandTaxonomy']);
        // add_action('saved_product_cat')
    }

    public function productBrandTaxonomy () {
        $slugCPT = 'product';
        $slugTax = 'brands';

        $labels = array(
            'name'              => _x('Brands', 'taxonomy general name'),
            'singular_name'     => _x('Brand', 'taxonomy singular name'),
            'search_items'      => __('Search Brands'),
            'all_items'         => __('All Brands'),
            'parent_item'       => __('Parent Brands'),
            'parent_item_colon' => __('Parent Brands:'),
            'edit_item'         => __('Edit Brands'),
            'update_item'       => __('Update Brands'),
            'add_new_item'      => __('Add New Brands'),
            'new_item_name'     => __('New Brands Name'),
            'menu_name'         => __('Brands'),
        );

        $args = [
            'label' => _x('Brands', 'taxonomy general name'),
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'            => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => $slugTax),
        ];

        $this->taxonomy($slugCPT, $slugTax, $args);
    }

    public function productBrandMetabox () {
        remove_meta_box('tagsdiv-brands', 'product', 'side');
    }

    // public function productDiscountTaxonomy () {
    //     $slugCPT = 'product';
    //     $slugTax = 'discount';

    //     $labels = array(
    //         'name'              => _x('Discount', 'taxonomy general name'),
    //         'singular_name'     => _x('Discount', 'taxonomy singular name'),
    //         'search_items'      => __('Search Discount'),
    //         'all_items'         => __('All Discount'),
    //         'parent_item'       => __('Parent Discount'),
    //         'parent_item_colon' => __('Parent Discount:'),
    //         'edit_item'         => __('Edit Discount'),
    //         'update_item'       => __('Update Discount'),
    //         'add_new_item'      => __('Add New Discount'),
    //         'new_item_name'     => __('New Discount Name'),
    //         'menu_name'         => __('Discount'),
    //     );

    //     $args = [
    //         'label' => _x('Discount', 'taxonomy general name'),
    //         'hierarchical'      => false,
    //         'labels'            => $labels,
    //         'show_ui'           => true,
    //         'show_admin_column' => true,
    //         'query_var'         => true,
    //         'public'            => true,
    //         'show_in_rest'      => true,
    //         'rewrite'           => array('slug' => $slugTax),
    //     ];

    //     $this->taxonomy($slugCPT, $slugTax, $args);
    // }
}

// Initiate
new ProductCPT();
