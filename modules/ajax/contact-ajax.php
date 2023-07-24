<?php
defined('ABSPATH') || die('Direct Acces not allowed');

require_once MODULES_DIR . '/post/__register-post.php';

use Custom\Post\RegisterPost as RP;

class ContactAjax extends SanitizeAndValidate
{
    public $data = [
        'nonce' => false,
        'data'  => null
    ];
    protected $registerPost;

    public function __construct()
    {
        $this->registerPost = new RP();
        $this->data = [
            'nonce' => '',
            'contact-message-name' => '',
            'contact-message-email' => '',
            'contact-message-subject' => '',
            'contact-message-message' => ''
        ];
        add_action('wp_ajax_contact_message_handle', [$this, 'contactMessageHandle']);
        add_action('wp_ajax_nopriv_contact_message_handle', [$this, 'contactMessageHandle']);
    }

    public function contactMessageHandle()
    {
        $this->data = $this->main($this->data, $_POST, '_contact_nonce');

        $validate = $this->_validate_message($this->data);
        if (!$validate['is_valid']) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid user input.',
                'errors'  => $validate['errors']
            ], 400);
        } else {
            $arguments = [
                'post_content' => $this->data['contact-message-message'],
                'post_date' => date('Y-m-d H:i:s', time()),
                'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => 0,
            ];

            $message = $this->registerPost->makeMessage($this->data['contact-message-subject'], 'shop-messages', 'default', $arguments);
            if (!$message) {
                wp_send_json([
                    'success' => false,
                    'message' => 'Failed to store message.'
                ], 500);
            }
            $metaMail = update_post_meta($message, '_message_email', $this->data['contact-message-email']);
            $metaName = update_post_meta($message, '_message_name', $this->data['contact-message-name']);

            $admin = get_users('role=Administrator');
            foreach ($admin as $user) {
                /** Send message to admin email */
                $headers[] = 'From: ' . $metaName . '<' . $metaMail . '>';
                wp_mail($user->user_email, 'SUBJECT', $this->data['contact-message-message'], $headers);
            }

            wp_send_json([
                'success' => true,
                'message' => 'Message sent!'
            ], 200);
        }
    }

    /**
     * _validate_message
     *
     * for lam (leave a message)
     *
     * @param array $request
     * @return void
     */
    private function _validate_message(array $request)
    {
        $response = [
            'is_valid' => true,
            'errors'   => []
        ];

        /** validate email */
        if (!isset($request['contact-message-email']) || $request['contact-message-email'] === '') {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is required';
        }

        /** validate name */
        if (!isset($request['contact-message-name']) || $request['contact-message-name'] === '') {
            $response['is_valid'] = false;
            $response['errors']['name'][] = 'name is required';
        }

        /** validate subject */
        if (!isset($request['contact-message-subject']) || $request['contact-message-subject'] === '') {
            $response['is_valid'] = false;
            $response['errors']['subject'][] = 'subject is required';
        }

        return $response;
    }
}

new ContactAjax();
