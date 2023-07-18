<?php
defined('ABSPATH') || die('Direct access not allowed');

class ProductACF {
    public function __construct () {
        $this->productSpecsACF();
    }

    public function productSpecsACF () {
        if (function_exists('acf_add_local_field_group')) {
            // echo 'a';
            $brands = get_taxonomies(['name' => 'Brand'], 'object');
            // print("<pre>".print_r($brands, true)."</pre>");
            acf_add_local_field_group ([
                'key' => 'field_product_spesification',
                'title' => 'Product Spesification Overview',
                'fields' => [
                    [
                        'key' => 'field_product_brand',
                        'label' => __('Product Brand'),
                        'name'  => 'product_brand',
                        'type'  => 'select',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => 'acf-product-brand'
                        ],
                        'choices' => $brands,
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'default_value' => '',
                        'placeholder' => 'select product status',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ]
                ],
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'product',
                        ),
                    ),
                ),
                'menu_order' => 3,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ]);
        }
    }
}

// Initiate
new ProductACF();
