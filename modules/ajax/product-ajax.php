<?php
defined('ABSPATH') || die('Direct Acces not allowed');

class ProductAjax extends SanitizeAndValidate
{
    private $data = [
        'nonce' => false,
        'item' => null,
        'product_id' => null,
        'quantity' => null,
        'variation_id' => null,
    ];

    public function __construct()
    {
        add_action('wp_ajax_custom_woocommerce_add_to_cart', [$this, 'woocommerceAddToCart']); // woocommerceAddToCart (Woocommerce Add to Cart)
        add_action('wp_ajax_nopriv_custom_woocommerce_add_to_cart', [$this, 'woocommerceAddToCart']);
    }

    public function woocommerceAddToCart()
    {
        $id = sanitize_text_field($_POST['product_id']);
        $product = wc_get_product($_POST['product_id']);

        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $id)) {
            $validate = $this->_validate_games_category_meta($_POST);

            if (!$validate['is_valid']) {
                wp_send_json([
                    'success' => false,
                    'message' => 'Failed adding to cart.'
                ], 400);
            } else {
                $metas['metadata'][] = [
                    '_item_platform' => sanitize_text_field($_POST['meta-platform']),
                    '_item_account' => sanitize_text_field($_POST['meta-account'])
                ];

                if (!WC()->cart->is_empty()) {
                    $cart = WC()->cart;
                    $cart_contents = $cart->cart_contents;
                    foreach ($cart_contents as $key => $item) {
                        if ($item['product_id'] == $id) {
                            $qty = intval($item['quantity']) + intval($_POST['quantity']);

                            $metas = $cart_contents[$key]['metadata'] ?? [];

                            array_push($metas, [
                                '_item_platform' => sanitize_text_field($_POST['meta-platform']),
                                '_item_account' => sanitize_text_field($_POST['meta-account'])
                            ]);

                            $cart_contents[$key]['metadata'] = $metas;
                            WC()->cart->set_cart_contents($cart_contents);
                            $addToCart = WC()->cart->set_quantity($key, $qty);
                            break;
                        } else {
                            $addToCart = WC()->cart->add_to_cart($id, $_POST['quantity'], 0, [], $metas);
                        }
                    }
                } else {
                    $addToCart = WC()->cart->add_to_cart($id, $_POST['quantity'], 0, [], $metas);
                }

                if ($addToCart) {
                    wp_send_json([
                        'success' => true,
                        'message' => 'Added to cart.'
                    ], 200);
                } else {
                    wp_send_json([
                        'success' => false,
                        'message' => 'Failed adding to cart.'
                    ], 500);
                }
            }
        } else {
            foreach ($_POST as $key => $values) {
                if (str_contains($key, 'attribute_')) {
                    $this->data['attribute'][] = [
                        sanitize_text_field($key) => sanitize_text_field($values)
                    ];
                }
            }

            $addToCart = WC()->cart->add_to_cart($id, $_POST['quantity'], $_POST['variation_id'], [], null);

            if ($addToCart) {
                wp_send_json([
                    'success' => true,
                    'message' => 'Added to cart.'
                ], 200);
            } else {
                wp_send_json([
                    'success' => false,
                    'message' => 'Failed adding to cart.'
                ], 500);
            }
        }
    }

    private function _validate_games_category_meta(array $request)
    {
        $response = [
            'is_valid' => true,
            'errors' => []
        ];

        /** Validate platform */
        if (!isset($request['meta-platform'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-platform'][] = 'Platform is required.';
        } else if (in_array(strtolower($request['meta-platform']), ['steam', 'epic-games', 'origin'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-platform'][] = 'Platform is already used.';
        } else {
            $response['is_valid'] = true;
            $response['errors']['meta-platform'] = [];
        }

        /** Validate id */
        if (!isset($request['meta-account'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-account'][] = 'Account ID is required.';
        } else {
            $response['is_valid'] = true;
            $response['errors']['meta-account'] = [];
        }

        return $response;
    }
}

// Initiate
new ProductAjax();
