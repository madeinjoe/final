<?php
defined('ABSPATH') || die('Direct Acces not allowed');

require_once MODULES_DIR . '/post/__register-post.php';
use Custom\Post\RegisterPost as RP;

class ContactAjax extends SanitizeAndValidate {
    public $data = [
        'nonce' => false,
        'data'  => null
    ];
    protected $registerPost;

    public function __construct () {
        $this->registerPost = new RP();
        $this->data = [
            'nonce' => false,
            'lam-name' => '',
            'lam-email' => '',
            'lam-subject' => '',
            'lam-message' => ''
        ];
        add_action('wp_ajax_leave_a_message', [$this, 'lamHandle']);
        add_action('wp_ajax_nopriv_leave_a_message', [$this, 'lamHandle']);
        add_action('wp_enqueue_scripts', [$this, 'lamScripts']);
    }

    public function lamScripts () {
        global $post;
        if (!empty($post) && $post->post_name === 'contact-us') {
            wp_enqueue_script('lam-js', ASSETSURI.'/custom/ajax/leave-a-message.js');
            wp_localize_script('lam-js', 'lamData', ['url' => admin_url('admin-ajax.php'), 'action' => 'leave_a_message', 'token' => wp_create_nonce('_lam_nonce')]);
        }
    }

    public function lamHandle () {
        $data = [
            'nonce' => false,
            'lam-name' => '',
            'lam-email' => '',
            'lam-subject' => '',
            'lam-message' => ''
        ];

        $this->data = $this->main($data, $_POST, '_lam_nonce');

        $arguments = [
            'post_content' => $this->data['lam-message'],
            'post_date' => date('Y-m-d H:i:s', time()),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_parent' => 0,
        ];

        $validate = $this->_validate_lam($this->data);
        if (!$validate['is_valid']) {
            return wp_send_json([
                'success' => false,
                'message' => 'Invalid user input.',
                'errors'  => $validate['errors']
            ], 400);
        } else {
            // return wp_send_json([
            //     'success' => false,
            //     'message' => $this->data
            // ], 200);
            $message = $this->registerPost->makePost($this->data['lam-subject'], 'shop-messages', ['administrator', 'editor', 'author', 'contributor', 'subscriber'], 'default', $arguments);
            if (!$message) {
                return wp_send_json([
                    'success' => false,
                    'message' => 'Failed to store message.'
                ], 500);
            }
            update_post_meta($message, '_message_email', $this->data['lam-email']);
            update_post_meta($message, '_message_name', $this->data['lam-name']);
        }
    }

    private function _validate_lam (Array $request) {
        $response = [
            'is_valid' => true,
            'errors'   => []
        ];

        /** validate email */
        if (!isset($request['lam-email'])) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is required';
        }

        /** validate name */
        if (!isset($request['lam-name'])) {
            $response['is_valid'] = false;
            $response['errors']['name'][] = 'name is required';
        }

        /** validate subject */
        if (!isset($request['lam-subject'])) {
            $response['is_valid'] = false;
            $response['errors']['subject'][] = 'subject is required';
        }

        return $response;
    }
}

new ContactAjax();
