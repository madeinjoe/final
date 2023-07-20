<?php
defined('ABSPATH') || die('Direct access not allowed');

class middleWoocommerce
{
    public function __construct()
    {
        /** Calculate Price */
        add_action('woocommerce_before_calculate_totals', [$this, 'wooCartItemPrice'], 10, 1);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'wooShopProductsPrice']);
        add_action('woocommerce_single_product_summary', [$this, 'wooShopProductsPrice']);

        /** counter */
        add_action('woocommerce_before_shop_loop_item', [$this, 'wooShopProduct']);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'wooCountdownWrapper']);

        /** Single Product */
        add_action('woocommerce_before_single_product', [$this, 'wooSingleProduct']);

        /** Cart */
        add_action('woocommerce_before_add_to_cart_form', [$this, 'wooCountdownWrapper']);
        add_action('woocommerce_before_add_to_cart_quantity', [$this, 'wooItemCustomMeta']);
        add_filter('woocommerce_get_item_data', [$this, 'wooCartRenderMeta'], 10, 2);

        /** Order */
        add_action('woocommerce_order_item_meta_start', [$this, 'wooEmailItemMeta'], 10, 4);
        add_action('woocommerce_add_order_item_meta', [$this, 'wooAtcGamesMeta'], 10, 3);
        add_filter('wp_mail', [$this, 'wooOrderEmail'], 10, 1);
    }

    public function wooCartItemPrice($cart)
    {
        // print("<pre>".print_r($cart, true)."</pre>");
        foreach ($cart->cart_contents as $key => $value) {
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

    public function wooShopProductsPrice()
    {
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
        echo '<h1 class="text-3xl text-emerald-600">' . $price . '</h1>';
    }
    public function wooShopProduct()
    {
        global $product;
        $timeDiff = $this->productCountDownData($product);

        $output = '<span class="product-sale-date" data-id="' . $product->get_ID() . '">';

        if ($product->get_date_on_sale_from()) {
            $output .= '<span class="from" data-from="' . $product->get_date_on_sale_from()->date('Y-m-d H:i:s') . '"></span>';
        }

        if ($product->get_date_on_sale_to()) {
            $output .= '<span class="to" data-to="' . $product->get_date_on_sale_to()->date('Y-m-d H:i:s') . '"></span>';
        }

        if ($timeDiff) {
            $output .= '<span class="d" data-d="' . $timeDiff->d . '"></span>';
            $output .= '<span class="hr" data-hr="' . $timeDiff->h . '"></span>';
            $output .= '<span class="min" data-min="' . $timeDiff->m . '"></span>';
            $output .= '<span class="sec" data-sec="' . $timeDiff->s . '"></span>';
        }
        $output .= '</span>';

        echo $output;
    }

    public function wooCountdownWrapper()
    {
        global $product;

        echo '<div id="' . $product->get_ID() . '" class="flex flex-col mt-2">';
        echo '<span id="countdown-note"></span>';
        echo '<span id="countdown">
            <span id="days" class=""></span>
            <span id="hours"></span>
            <span id="minutes"></span>
            <span id="seconds"></span>
        </span>';
        echo '</div>';
    }

    public function wooSingleProduct()
    {
        global $product;

        if (is_single() && is_product()) {
            if ($product->get_date_on_sale_from() || $product->get_date_on_sale_to()) {
                $output = '<span class="product-sale-date" data-id="' . $product->get_ID() . '">';

                if ($product->get_date_on_sale_from()) {
                    $output .= '<span class="from" data-from="' . $product->get_date_on_sale_from()->date('Y-m-d H:i:s') . '"></span>';
                }

                if ($product->get_date_on_sale_to()) {
                    $output .= '<span class="to" data-to="' . $product->get_date_on_sale_to()->date('Y-m-d H:i:s') . '"></span>';
                }

                $output .= '</span>';

                echo $output;
            }
        }
    }

    public function wooItemCustomMeta()
    {
        global $product;
        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
            // echo '<div id="form-custom-meta">';
            echo '<input type="hidden" name="item" value="' . $product->get_ID() . '">';
            echo '<input type="hidden" name="action" value="woo_atc_games">';
            echo '<input type="hidden" name="url" value="' . admin_url('admin-ajax.php') . '">';
            wp_nonce_field('_atc_games', 'nonce');
            echo '<div class="input-group">';
            echo '<label for="custom-meta-1">Select Platform</label>';
            echo '<select id="custom-meta-1" name="meta-slct" class="form-control" required>';
            echo '<option value="">Select Platform</option>';
            echo '<option value="Steam">Steam</option>';
            echo '<option value="EGS">Epic Games Store</option>';
            echo '<option value="Origin">Origin</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="input-group">';
            echo '<label for="custom-meta-2">Input Account ID</label>';
            echo '<input id="custom-meta-2" name="meta-input" class="form-control" required="required" />';
            echo '</div>';
            // echo '</div>';
        }
    }

    public function wooCartRenderMeta($data, $cart_item)
    {
        // if (array_key_exists('metadata', $cart_item)) {
        array_push($data, [
            'name' => 'Platform',
            'value' => $cart_item['metadata']['_item_platform'] . ' - ' . $cart_item['metadata']['_item_account']
        ]);
        // }

        return $data;
    }

    public function wooAtcGamesMeta($itemId, $values, $key)
    {
        $session_var = 'sess_cart_games';
        $session_data = WC()->session->get($session_var);
        if (!empty($session_data)) {
            wc_add_order_item_meta($itemId, '_item_platform', $values['metadata']['_item_platform']);
            wc_add_order_item_meta($itemId, '_item_account', $values['metadata']['_item_account']);
        }
    }

    public function wooEmailItemMeta($item_id, $item, $order, $do_plain)
    {
        echo '<hr>';
        echo 'Account ' . ' : [' . $item->get_meta('_item_platform') . '] ' . $item->get_meta('_item_account');
    }

    public function wooOrderEmail($headers)
    {
        $headers[] = 'From: ' . WPMS_MAIL_FROM_NAME . '<' . WPMS_MAIL_FROM . '>';
        return $headers;
    }
    private function productCountDownData($product)
    {
        $currentTime = new DateTime('now');
        $fromTime = $product->get_date_on_sale_from() ? new DateTime($product->get_date_on_sale_from()->date('Y-m-d H:i:s')) : null;
        $toTime = $product->get_date_on_sale_to() ? new DateTime($product->get_date_on_sale_to()->date('Y-m-d H:i:s')) : null;

        if ($fromTime !== null) {
            return $fromTime->diff($currentTime);
        } else if ($toTime !== null) {
            return $currentTime->diff($toTime);
        } else {
            return [];
        }
    }
}

// Initiate
new middleWoocommerce();