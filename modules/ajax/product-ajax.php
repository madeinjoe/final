<?php
defined('ABSPATH') || die('Direct Acces not allowed');

class ProductAjax extends SanitizeAndValidate
{
    private $data = [
        'nonce' => false,
        'data' => null
    ];

    public function __construct()
    {
        add_action('wp_ajax_woo_atc_games', [$this, 'wooAtcGames']);
        add_action('wp_ajax_nopriv_woo_atc_games', [$this, 'wooAtcGames']);
    }

    public function wooAtcGames()
    {
        $this->data = $this->main($this->data, $_POST, '_atc_games');
        $validate = $this->_validate_input($_POST);

        if (!$validate['is_valid']) {
            return wp_send_json([
                'success' => false,
                'message' => 'Invalid user input.',
                'errors' => $validate['errors']
            ], 400);
        } else {
            $id = sanitize_text_field($_POST['item']);

            /** Item meta */
            $metas['metadata']['_item_platform'] = sanitize_text_field($_POST['meta-slct']);
            $metas['metadata']['_item_account'] = sanitize_text_field($_POST['meta-input']);
            WC()->session->set('sess_cart_games', $metas);

            $addToCart = WC()->cart->add_to_cart($id, $_POST['quantity'], 0, [], $metas);

            if ($addToCart) {
                return wp_send_json([
                    'success' => true,
                    'message' => 'Added to cart.',
                    'meta' => $metas
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