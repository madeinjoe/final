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
        ''
    ];

    public function __construct()
    {
        add_action('wp_ajax_woo_atc_games', [$this, 'wooAtcGames']);
        add_action('wp_ajax_nopriv_woo_atc_games', [$this, 'wooAtcGames']);
        // add_action('woocommerce_add_cart');
    }

    public function wooAtcGames()
    {
        $id = $_POST['item'] ? sanitize_text_field($_POST['item']) : sanitize_text_field($_POST['product_id']);
        $product = wc_get_product($_POST['item']);

        if ($product->is_virtual('yes') && has_term('games', 'product_cat', $product->get_ID())) {

            $metas['metadata'][] = [
                '_item_platform' => sanitize_text_field($_POST['meta-slct']),
                '_item_account' => sanitize_text_field($_POST['meta-input'])
            ];


            if (!WC()->cart->is_empty()) {
                $cart = WC()->cart;
                $cart_contents = $cart->cart_contents;
                foreach ($cart_contents as $key => $item) {
                    if ($item['product_id'] == $id) {
                        $qty = intval($item['quantity']) + intval($_POST['quantity']);

                        $metas = $cart_contents[$key]['metadata'] ?? [];

                        array_push($metas, [
                            '_item_platform' => sanitize_text_field($_POST['meta-slct']),
                            '_item_account' => sanitize_text_field($_POST['meta-input'])
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
                WC()->session->set('sess_cart_games', $metas);
                return wp_send_json([
                    'success' => true,
                    'message' => 'Added to cart.',
                    'sess' => WC()->session->get('sess_cart_games')
                ], 200);
            } else {

                return wp_send_json([
                    'success' => false,
                    'message' => 'Failed adding to cart.',
                    'atc' => $addToCart,
                    'metas' => $metas
                ], 500);
            }
        } else {
            foreach ($_POST as $key => $values) {
                if (str_contains($key, 'attribute_')) {
                    $this->data['attribute'][] = [
                        sanitize_text_field($key) => sanitize_text_field($values)
                    ];
                }
            }

            $addToCart = WC()->cart->add_to_cart($_POST['item'], $_POST['quantity'], $_POST['variation_id'], [], null);

            if ($addToCart) {
                return wp_send_json([
                    'success' => true,
                    'message' => 'Added to cart.'
                ], 200);
            } else {
                return wp_send_json([
                    'success' => false,
                    'message' => 'Failed adding to cart.'
                ], 500);
            }
        }
    }

    private function _validate_input(array $request)
    {
        $response = [
            'is_valid' => true,
            'errors' => []
        ];

        /** Validate platform */
        if (!isset($request['meta-slct'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-slct'][] = 'Platform is required.';
        } else if (in_array($request['meta-slct'], ['steam', 'egs', 'origin'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-slct'][] = 'Platform is already used.';
        } else {
            $response['is_valid'] = true;
            $response['errors']['meta-slct'][] = '';
        }

        /** Validate id */
        if (!isset($request['meta-input'])) {
            $response['is_valid'] = false;
            $response['errors']['meta-input'][] = 'Account ID is required.';
        } else {
            $response['is_valid'] = true;
            $response['errors']['meta-input'][] = '';
        }

        return $response;
    }
}

// Initiate
new ProductAjax();
