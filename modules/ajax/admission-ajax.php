<?php
defined('ABSPATH') || die('Direct Acces not allowed');

class AdmissionAjax {
    private $data = [
        'nonce' => false,
        'data'  => null
    ];

    public function __construct () {
        add_action('wp_ajax_login_handle', [$this, 'loginHandle']);
        add_action('wp_ajax_nopriv_login_handle', [$this, 'loginHandle']);
        add_action('wp_ajax_registration_handle', [$this, 'registrationHandle']);
        add_action('wp_ajax_nopriv_registration_handle', [$this, 'registrationHandle']);
    }

    public function loginHandle () {
        if (!isset($_POST['login-username']) && !isset($_POST['login-password'])) {
            if (!isset($_POST['login-username'])) {
                $errorMsg['username'] = 'Username is required.';
            }

            if (!isset($_POST['login-password'])) {
                $errorMsg['password'] = 'Password is required.';
            }

            wp_send_json([
                'success' => false,
                'message' => 'Invalid Credential.',
                'errors' => $errorMsg
            ], 400);
        } else {
            $credentials = [
                'user_login' => sanitize_user($_POST['login-username']),
                'user_password' => sanitize_text_field($_POST['login-password']),
                'remember' => $_POST['login-remember'] ?? false
            ];

            $check = wp_signon($credentials, true);

            if (is_wp_error($check)) {
                $response = [
                    'success' => false,
                    'message' => 'Credentials is invalid.',
                    'errors' => $check
                ];
            } else {
                wp_set_current_user($check->ID);
                wp_set_auth_cookie($check->ID, $credentials['remember'], is_ssl());
                do_action('wp_login', $check->data->user_login, $check);

                switch (strtolower($check->roles[0])) {
                    case 'subscriber':
                    case 'buyer':
                        $postForSubs = get_page_by_path('/shop', 'object', 'post');
                        break;
                    default:
                        $redirect = admin_url('/');
                }

                wp_send_json([
                    'success' => true,
                    'message' => 'You\'re now logged in.',
                    'data'    => [
                        'session' => wp_get_all_session(),
                        'redirect' => $redirect
                    ]
                ], 200);
            }
        }
    }

    public function registrationHandle () {
        $this->data = $this->main($this->data, $_POST, '_register_nonce');
        $validate = $this->_validate_registration($_POST);

        if (!$validate['is_valid']) {
            return wp_send_json([
                'success' => false,
                'message' => 'Invalid user input.',
                'errors'  => $validate['errors']
            ], 400);
        } else {
            /** Add User */
            $add = wp_insert_user([
                'user_login' => $this->data['username'],
                'user_email' => $this->data['email'],
                'user_pass'  => wp_hash_password($_POST['password'])
            ]);

            if (is_wp_error($add)) {
                $response = [
                    'success' => false,
                    'message' => 'User input is invalid.',
                    'errors' => $add
                ];
            } else {
                /** Set user role to subscriber */
                $thisUser = new WP_User($add);
                $thisUser->set_role('subscriber');

                /** Update activation code */
                $activationCode = wp_hash($add.time());
                $activationPage = get_page_by_path('account-activation', 'object', 'page');
                $activationUrl  = add_query_arg(['acode' => $activationCode, 'user' => $add], get_permalink($activationPage->ID ?? 1));
                wp_update_user(['ID' => $add, 'user_activation_key' => $activationCode]);

                /** Send activation code */
                $headers[] = 'From: '.WPMS_MAIL_FROM_NAME.'<'.WPMS_MAIL_FROM.'>';
                wp_mail($this->data['email'], 'SUBJECT', 'Activation Link : '.$activationUrl, $headers);

                $redirect = get_permalink(get_page_by_path('login', 'object', 'page')->ID);

                return wp_send_json([
                    'success' => true,
                    'message' => 'Registration Success.',
                    'redirect' => $redirect
                ], 200);
            }
        }
    }

    private function _validate_registration (Array $request) {
        $response = [
            'is_valid'  => true,
            'errors'    => []
        ];

        /** Validate email */
        if (!isset($request['email'])) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is already used.';
        } else if (email_exists(sanitize_email($request['email']))) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is already used.';
        } else if (!is_email(sanitize_email($request['email']))) {
            $response['is_valid'] = false;
            $response['errors']['email'][] =  'email is invalid.';
        }

        /** Validate username */
        if (!isset($request['username'])) {
            $response['is_valid'] = false;
            $response['errors']['username'][] = 'username is required.';
        } else if (username_exists(sanitize_text_field($request['username']))) {
            $response['is_valid'] = false;
            $response['errors']['username'][] = 'username is required.';
        }

        /** Validate password and confirmation */
        if (!isset($request['password'])) {
            $response['is_valid'] = false;
            $response['errors']['password'][] = 'password is required.';
        } else if (strlen($request['password']) < 8) {
            $response['is_valid'] = false;
            $response['errors']['password'][] = 'password must have at least 8 character.';
        }
        if (!isset($request['re-password'])) {
            $response['is_valid'] = false;
            $response['errors']['re-password'][] = 'password confirmation is required.';
        } else if ($request['re-password'] !== $request['password']) {
            $response['is_valid'] = false;
            $response['errors']['re-password'][] = 'password confirmation doesn\'t match.';
        }

        return $response;
    }
}

// Initiate
new AdmissionAjax();
