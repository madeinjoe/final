<?php
defined('ABSPATH') || die('Direct access not allowed');

class middleWoocommerce {
    public function __construct () {
        add_action('woocommerce_before_calculate_totals', [$this, 'wooCartItemPrice'], 10, 1);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'wooShopProductsPrice']);
        add_action('woocommerce_single_product_summary', [$this, 'wooShopProductsPrice']);

        add_action('woocommerce_before_single_product', [$this, 'singleProductScript']);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'countdownEl']);
    }

    public function wooCartItemPrice ($cart) {
        // print("<pre>".print_r($cart, true)."</pre>");
        foreach ( $cart->cart_contents as $key => $value ) {
            $product = wc_get_product($value['product_id']);
            $cats = get_the_terms($value['product_id'], 'product_cat');
            $totalDisc = 0;

            foreach ($cats as $cat) {
                if (has_term_meta($cat->term_id) && get_term_meta($cat->term_id, 'has_discount', true)) {
                    $discType = get_term_meta($cat->term_id, 'discount_type', true);
                    $discAmount = get_term_meta($cat->term_id, 'discount_amount', true);

                    if (get_term_meta($cat->term_id, 'discount_type', true) === 'percentage') {
                        if (get_term_meta($cat->term_id, 'count_sequentially', true)) {
                            $totalDisc = $totalDisc + ((floatval($product->get_price()) - $totalDisc) * (floatval($discAmount) / 100));
                        } else {
                            $totalDisc = $totalDisc + floatval($product->get_price()) * (floatval($discAmount) / 100);
                        }
                    } else {
                        $totalDisc = $totalDisc + floatval($discAmount);
                    }
                }
            }
            $price = floatval($product->get_price()) - $totalDisc;
            // print("<pre>".print_r($price, true)."</pre>");
            $value['data']->set_price($price);
        }
    }

    public function wooShopProductsPrice () {
        global $product;

        $cats = get_the_terms($product->get_ID(), 'product_cat');
        $totalDisc = 0;

        foreach ($cats as $cat) {
            if (has_term_meta($cat->term_id) && get_term_meta($cat->term_id, 'has_discount', true)) {
                $discType = get_term_meta($cat->term_id, 'discount_type', true);
                $discAmount = get_term_meta($cat->term_id, 'discount_amount', true);

                if (get_term_meta($cat->term_id, 'discount_type', true) === 'percentage') {
                    if (get_term_meta($cat->term_id, 'count_sequentially', true)) {
                        $totalDisc = $totalDisc + ((floatval($product->get_price()) - $totalDisc) * (floatval($discAmount) / 100));
                    } else {
                        $totalDisc = $totalDisc + floatval($product->get_price()) * (floatval($discAmount) / 100);
                    }
                } else {
                    $totalDisc = $totalDisc + floatval($discAmount);
                }
            }
        }

        $price = floatval($product->get_price()) - $totalDisc;
        echo '<h1 class="text-3xl text-emerald-600">'.$price.'</h1>';
    }

    public function singleProductScript () {
        if (is_single() && is_product()) {
            global $product;

            $nowDate = new DateTime('now');
            $toDate = new DateTime($product->get_date_on_sale_to()->date('Y-m-d H:i:s'));
            $diff = $nowDate->diff($toDate);

            wp_enqueue_script('single-product-js', ASSETSURI . '/custom/js/single-product-script.js', ['jquery']);
            wp_localize_script('single-product-js', 'productData', [
                'action' => 'woo_add_to_cart',
                'item' => $product->get_id(),
                'item_sale_from' => $product->get_date_on_sale_from(),
                'item_sale_to' => $product->get_date_on_sale_to(),
                'item_sale_diff' => [
                    'days'  => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->m,
                    'seconds' => $diff->s
                ]
            ]);
        }
    }

    public function countdownEl() {
        echo '<span id="countdown">
            <span id="days"></span>
            <span id="hours"></span>
            <span id="minutes"></span>
            <span id="seconds"></span>
        </span>';
    }
}

// Initiate
new middleWoocommerce();
