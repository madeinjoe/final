<?php
defined('ABSPATH') || die('Direct Acces not allowed');

class AdmissionAjax extends SanitizeAndValidate
{
    private $data = [];

    public function __construct()
    {
        add_action('wp_ajax_login_handle', [$this, 'loginHandle']);
        add_action('wp_ajax_nopriv_login_handle', [$this, 'loginHandle']);
        add_action('wp_ajax_registration_handle', [$this, 'registrationHandle']);
        add_action('wp_ajax_nopriv_registration_handle', [$this, 'registrationHandle']);
    }

    public function loginHandle()
    {
        /** Verify nonce */
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], '_custom_login')) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid nonce token.',
                'errors' => [
                    'nonce' => ['nonce token is invalid']
                ]
            ], 400);
        }

        if (!isset($_POST['login-username']) && !isset($_POST['login-password'])) {
            if (!isset($_POST['login-username'])) {
                $errorMsg['username'] = 'Username / Email is required.';
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
            if (is_email($_POST['login-username'])) {
                $user = get_user_by('email', sanitize_email($_POST['login-username']));
                $credentials = [
                    'user_login' => $user->user_login,
                    'user_password' => sanitize_text_field($_POST['login-password']),
                    'remember' => $_POST['login-remember'] ?? false
                ];
            } else {
                $credentials = [
                    'user_login' => sanitize_user($_POST['login-username']),
                    'user_password' => sanitize_text_field($_POST['login-password']),
                    'remember' => $_POST['login-remember'] ?? false
                ];
            }

            $check = wp_signon($credentials, true);

            if (is_wp_error($check)) {
                wp_send_json([
                    'success' => false,
                    'message' => 'Credentials is invalid.',
                    'errors' => $check
                ], 400);
            } else {
                wp_set_current_user($check->ID);
                wp_set_auth_cookie($check->ID, $credentials['remember'], is_ssl());
                // do_action('wp_login', $check->data->user_login, $check);

                switch (strtolower($check->roles[0])) {
                    case 'subscriber':
                    case 'customer':
                        $redirect = get_permalink(get_page_by_path('/shop', 'object', 'page')->ID);
                        break;
                    case 'editor':
                    case 'contributor':
                        $redirect = admin_url('edit.php');
                        break;
                    default:
                        $redirect = admin_url('/');
                }

                $message = 'You\'re now logged in.';
                if ($check->data->user_status == 1) {
                    $message .= ' Please activate your account to use all feature avaiable!';
                }

                wp_send_json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'redirect' => $redirect
                    ]
                ], 200);
            }
        }
    }

    public function registrationHandle()
    {
        $this->data = [
            'nonce' => $_POST['nonce'],
            'registration-username' => $_POST['registration-username'],
            'registration-email' => $_POST['registration-email'],
            'registration-password' => $_POST['registration-password'],
            'registration-re-password' => $_POST['registration-re-password']
        ];
        $this->data = $this->main($this->data, $_POST, '_custom_registration');

        $validate = $this->_validate_registration($_POST);

        if (!$validate['is_valid']) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid user input.',
                'errors'  => $validate['errors']
            ], 400);
        } else {
            /** Add User */
            $add = wp_insert_user([
                'user_login' => $this->data['registration-username'],
                'user_email' => $this->data['registration-email'],
                'user_pass'  => wp_hash_password($this->data['registration-password']),
                'user_status' => 1
            ]);

            if (is_wp_error($add)) {
                wp_send_json([
                    'success' => false,
                    'message' => 'Invalid user input.',
                    'errors' => $add
                ], 400);
            } else {
                /** Set user role to subscriber */
                $thisUser = new WP_User($add);
                $thisUser->set_role('subscriber');

                /** Update activation code */
                $activationCode = wp_hash($add . time());
                $activationPage = get_page_by_path('account-activation', 'object', 'page');
                $activationUrl  = add_query_arg(['acode' => $activationCode, 'user' => $add], get_permalink($activationPage->ID ?? 1));
                wp_update_user(['ID' => $add, 'user_activation_key' => $activationCode, 'user_status' => 1]);

                /** Send activation code */
                $headers[] = 'From: ' . WPMS_MAIL_FROM_NAME . '<' . WPMS_MAIL_FROM . '>';
                wp_mail($this->data['registration-email'], 'SUBJECT', 'Activation Link : ' . $activationUrl, $headers);

                $pageLogin = get_page_by_path('login', 'object', 'page');
                if ($pageLogin) {
                    $redirect = get_permalink($pageLogin->ID);
                }

                wp_send_json([
                    'success' => true,
                    'message' => 'Registration Success. Please check your email for activation link.',
                    'data' => ['redirect' => $redirect]
                ], 200);
            }
        }
    }

    private function _validate_registration(array $request)
    {
        $response = [
            'is_valid' => true,
            'errors' => []
        ];

        /** Validate email */
        if (!isset($request['registration-email'])) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is required.';
        } else if (email_exists(sanitize_email($request['registration-email']))) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email already used.';
        } else if (!is_email(sanitize_email($request['registration-email']))) {
            $response['is_valid'] = false;
            $response['errors']['email'][] = 'email is invalid.';
        }

        /** Validate username */
        if (!isset($request['registration-username'])) {
            $response['is_valid'] = false;
            $response['errors']['username'][] = 'username is required.';
        } else if (username_exists(sanitize_text_field($request['registration-username']))) {
            $response['is_valid'] = false;
            $response['errors']['username'][] = 'username already used.';
        }

        /** Validate password and confirmation */
        if (!isset($request['registration-password'])) {
            $response['is_valid'] = false;
            $response['errors']['password'][] = 'password is required.';
        } else if (strlen($request['registration-password']) < 8) {
            $response['is_valid'] = false;
            $response['errors']['password'][] = 'password must have at least 8 character.';
        }
        if (!isset($request['registration-re-password'])) {
            $response['is_valid'] = false;
            $response['errors']['re-password'][] = 'password confirmation is required.';
        } else if ($request['registration-re-password'] !== $request['registration-password']) {
            $response['is_valid'] = false;
            $response['errors']['re-password'][] = 'password confirmation doesn\'t match.';
        }

        return $response;
    }
}

// Initiate
new AdmissionAjax();
