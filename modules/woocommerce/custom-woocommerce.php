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
        add_action('woocommerce_before_shop_loop_item', [$this, 'wooShop']);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'wooShopProduct']);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'wooCountdownWrapper']);

        /** Single Product */
        add_action('woocommerce_before_single_product', [$this, 'wooSingleProduct']);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'wooCountdownWrapper']);
        add_action('woocommerce_before_add_to_cart_quantity', [$this, 'wooItemCustomMeta']);

        /** Cart */
        add_filter('woocommerce_get_item_data', [$this, 'wooCartRenderMeta'], 10, 2);
        add_filter('woocommerce_cart_item_quantity', [$this, 'wooCartQuantity'], 10, 3);

        /** Order */
        add_action('woocommerce_order_item_meta_start', [$this, 'wooEmailItemMeta'], 10, 4);
        add_action('woocommerce_add_order_item_meta', [$this, 'wooAtcGamesMeta'], 10, 3);
        // add_action('woocommerce_checkout_create_order_line_item', [$this, 'wooAtcGamesMeta'], 10, 4);
        // add_filter('wp_mail', [$this, 'wooOrderEmail'], 10, 1);
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
        // $display = number_format($price, 2, ',', '.');
        $display = wc_price($price);
        if (floatval($product->get_price()) > floatval($price)) {
            echo '<h1 class="text-lg text-gray-300">' . wc_price($product->get_price()) . '</h1>';
        }
        echo '<h1 class="text-3xl text-emerald-600">' . wc_price($price) . '</h1>';
    }

    public function wooShop()
    {
        global $product;

        if ($product->is_type('external')) {
            echo '<span class="absolute top-0 right-0 px-2 text-sm text-white rounded-l w-fit whitespace-nowrap bg-black/60">External Product</span>';
        } else if ($product->is_type('variable')) {
            echo '<span class="absolute top-0 right-0 px-2 text-sm text-white rounded-l w-fit whitespace-nowrap bg-black/60">Variable Product</span>';
        } else if ($product->is_virtual()) {
            echo '<span class="absolute top-0 right-0 px-2 text-sm text-white rounded-l w-fit whitespace-nowrap bg-black/60">Virtual Product</span>';
        }
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

        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
            $output .= '<span class="virtual-games" data-id="' . $product->get_ID() . '"></span>';
        }

        echo $output;
    }

    public function wooCountdownWrapper()
    {
        global $product;

        $currentTime = new DateTime('now');
        $fromTime = $product->get_date_on_sale_from() ? new DateTime($product->get_date_on_sale_from()->date('Y-m-d H:i:s')) : null;
        $toTime = $product->get_date_on_sale_to() ? new DateTime($product->get_date_on_sale_to()->date('Y-m-d H:i:s')) : null;
        if (($product->get_date_on_sale_from() || $product->get_date_on_sale_to()) && ($fromTime > $currentTime || $currentTime < $toTime)) {
            echo '<div id="' . $product->get_ID() . '" class="flex flex-col mt-2">';
            echo '<span id="countdown-note"></span>';
            echo '<span id="countdown" class="flex gap-1 flex-nowrap">
                    <span class="flex flex-col p-2 text-center bg-white border border-gray-200 border-solid rounded w-fit">
                        <span id="days" class="font-bold"></span>
                        Days
                    </span>
                    <span class="flex flex-col p-2 text-center bg-white border border-gray-200 border-solid rounded w-fit">
                        <span id="hours" class="font-bold"></span>
                        Hr
                    </span>
                    <span class="flex flex-col p-2 text-center bg-white border border-gray-200 border-solid rounded w-fit">
                        <span id="minutes" class="font-bold"></span>
                        Min
                    </span>
                    <span class="flex flex-col p-2 text-center bg-white border border-gray-200 border-solid rounded w-fit">
                        <span id="seconds" class="font-bold"></span>
                        Sec
                    </span>
                </span>';
            echo '</div>';
        }
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
            echo '<input type="hidden" name="item" value="' . $product->get_ID() . '">';
            echo '<input type="hidden" name="action" value="woo_atc_games">';
            echo '<input type="hidden" name="url" value="' . admin_url('admin-ajax.php') . '">';
            // echo '<div id="form-custom-meta">';
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
        if (array_key_exists('metadata', $cart_item)) {
            foreach ($cart_item['metadata'] as $key => $values) {
                array_push($data, [
                    'name' => 'Platform',
                    'value' => $values['_item_platform'] . ' - ' . $values['_item_account']
                ]);
            }
        }

        return $data;
    }

    public function wooAtcGamesMeta($itemId, $cart_items, $cart_item_key)
    // public function wooAtcGamesMeta($item, $cart_item_key, $cart_items, $order)
    {
        wc_add_order_item_meta($itemId, 'metadata', $cart_items['metadata']);
        // $session_var = 'sess_cart_games';
        // $session_data = WC()->session->get($session_var);
        // if (!empty($session_data)) {
        // }
    }

    public function wooCartQuantity($product_quantity, $cart_item_key, $cart_item)
    {
        $product = wc_get_product($cart_item['product_id']);

        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
            return '<span>' . $product_quantity . '</span>';
        } else {
            return $product_quantity;
        }
    }

    public function wooEmailItemMeta($item_id, $item, $order, $do_plain)
    {
        $meta = wc_get_order_item_meta($item_id, 'metadata', true);
        if ($meta) {
            foreach ($meta as $key => $value) {
                echo '<hr>';
                echo 'Account ' . ' : [' . $value['_item_platform'] . '] ' . $value['_item_account'];
            }
            // print("<pre>" . print_r(wc_get_order_item($item_id), true) . "</pre>");
            // print("<pre>" . print_r(wc_get_order_item_meta($item_id, 'ci', true), true) . "</pre>");
            // print("<pre>" . print_r(wc_get_order_item_meta($item_id, 'metadata', true), true) . "</pre>");
            // print("<pre>" . print_r($item->get_ID(), true) . "</pre>");
        }
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
