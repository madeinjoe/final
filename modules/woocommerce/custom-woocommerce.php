<?php
defined('ABSPATH') || die('Direct access not allowed');

class customWoocommerce
{
    public function __construct()
    {
        add_action('woocommerce_before_calculate_totals', [$this, 'wooCartItemPrice'], 10, 1);

        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'initAfterShopLoopTitle']);

        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
        add_action('woocommerce_single_product_summary', [$this, 'woocommerceShopProductsPrice']);

        add_action('woocommerce_before_shop_loop_item', [$this, 'woocommerceShop']);

        add_action('woocommerce_before_single_product', [$this, 'woocommerceSingleProduct']);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'woocommerceCountdownWrapper']);
        add_action('woocommerce_before_add_to_cart_quantity', [$this, 'woocommerceItemCustomMeta']);

        /** Cart */
        add_filter('woocommerce_get_item_data', [$this, 'woocommerceCartRenderMeta'], 10, 2);
        add_filter('woocommerce_cart_item_quantity', [$this, 'woocommerceCartQuantity'], 10, 3);
        add_filter('woocommerce_cart_collaterals', [$this, 'woocommerceCartShortcode']);

        /** Order */
        add_action('woocommerce_order_item_meta_start', [$this, 'woocommerceEmailItemMeta'], 10, 4);
        add_action('woocommerce_add_order_item_meta', [$this, 'woocommerceAddOrderItemMeta'], 10, 3);
    }

    public function initAfterShopLoopTitle()
    {
        $this->woocommerceShopProductsPrice();
        $this->woocommerceShopProduct();
        $this->woocommerceCountdownWrapper();
    }

    public function wooCartItemPrice($cart)
    {
        // print("<pre>".print_r($cart, true)."</pre>");
        foreach ($cart->cart_contents as $key => $value) {
            $product = wc_get_product($value['product_id']);
            $categories = get_the_terms($value['product_id'], 'product_cat');
            $totalDisc = 0;

            foreach ($categories as $category) {
                if (has_term_meta($category->term_id) && get_term_meta($category->term_id, 'has_discount', true)) {
                    $discAmount = get_term_meta($category->term_id, 'discount_amount', true);

                    if (get_term_meta($category->term_id, 'discount_type', true) === 'percentage') {
                        if (get_term_meta($category->term_id, 'count_sequentially', true)) {
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
            $value['data']->set_price($price);
        }
    }

    public function woocommerceCartShortcode()
    {
        global $post;

        $cart = WC()->cart;
        $cartTotal = $cart->total;

        $cartCategoyIds = [];
        $productInCart = [];

        foreach ($cart->get_cart() as $key => $item) {
            $cartCategoyIds = array_unique(array_merge($cartCategoyIds, $item['data']->get_category_ids()));
            $productInCart[] = $item['product_id'];
        }

        $givenLimit = get_post_meta($post->ID, 'show_collaterals_when_limit', true);

        if ($cartTotal >= $givenLimit) {
            echo do_shortcode('[testshortcode categories="' . implode(',', $cartCategoyIds) . '" products="' . implode(',', $productInCart) . '"]');
        }
    }

    public function woocommerceShopProductsPrice()
    {
        global $product;

        $cats = get_the_terms($product->get_ID(), 'product_cat');
        $totalDisc = 0;

        foreach ($cats as $cat) {
            if (has_term_meta($cat->term_id) && get_term_meta($cat->term_id, 'has_discount', true)) {
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

    public function woocommerceShop()
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

    public function woocommerceShopProduct()
    {
        global $product;
        $timeDiff = $this->_productCountDownData($product);

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

    public function woocommerceCountdownWrapper()
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

    public function woocommerceSingleProduct()
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


            if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
                echo '<span id="virtual-games" data-type="virtual" data-category="games"></span>';
            }
        }
    }

    public function woocommerceItemCustomMeta()
    {
        global $product;
        echo '<input type="hidden" name="product_id" value="' . $product->get_ID() . '">';
        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
            echo '<div class="input-group">';
            echo '<label for="custom-meta-platform">Select Platform</label>';
            echo '<select id="custom-meta-platform" name="meta-platform" class="form-control" required>';
            echo '<option value="">Select Platform</option>';
            echo '<option value="Steam">Steam</option>';
            echo '<option value="Epic-Games">Epic Games Store</option>';
            echo '<option value="Origin">Origin</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="input-group">';
            echo '<label for="custom-meta-account">Input Account ID</label>';
            echo '<input id="custom-meta-account" name="meta-account" class="form-control" required="required" />';
            echo '</div>';
            // echo '</div>';
        }
    }

    public function woocommerceCartRenderMeta($data, $cart_item)
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

    public function woocommerceAddOrderItemMeta($itemId, $cart_items, $cart_item_key)
    {
        wc_add_order_item_meta($itemId, 'metadata', $cart_items['metadata']);
    }

    public function woocommerceCartQuantity($product_quantity, $cart_item_key, $cart_item)
    {
        $product = wc_get_product($cart_item['product_id']);

        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {
            return '<span>' . $product_quantity . '</span>';
        } else {
            return $product_quantity;
        }
    }

    public function woocommerceEmailItemMeta($item_id, $item, $order, $do_plain)
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

    private function _productCountDownData($product)
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
new customWoocommerce();
