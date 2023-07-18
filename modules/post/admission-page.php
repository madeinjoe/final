<?php
defined('ABSPATH') || die('Direct Acces not allowed');

class AdmissionPage extends RegisterPost {
    public function __construct () {
        add_action('after_setup_theme', [$this, 'loginPage']);
        add_action('after_setup_theme', [$this, 'registrationPage']);
        add_action('after-setup_theme', [$this, 'activationPage']);
    }

    public function loginPage () {
        $permalink = 'login';
        if (!get_page_by_path($permalink)) {
            $template = 'template-parts/custom-login.php';
            $args = [
                'post_name' => $permalink,
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s', time()),
                'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => 0,

            ];

            $this->makePost('Login Here', 'page', ['administrator'], $template, $args, $permalink);
        }
    }

    public function registrationPage () {
        /** Custom registration */
        $permalink = 'custom-registration';
        $template = 'template-parts/custom-registration.php';

        if (!get_page_by_path($permalink)) {
            $args = [
                'post_name' => $permalink,
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s', time()),
                'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => 0,
            ];
            $this->makePost('Register Now!', 'page', ['administrator'], $template, $args, $permalink);
        }
    }

    public function activationPage () {
        /** Custom activation */
        $activationPermalink = 'account-activation';
        $activationTemplate = 'template-parts/custom-activation.php';

        if (!get_page_by_path($activationPermalink)) {
            /** Custom activation */
            $activationAargs = [
                'post_name' => $activationPermalink,
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s', time()),
                'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => 0,
            ];

            $this->makePost('Your account is activated!', 'page', ['administrator'], $activationTemplate, $activationAargs, $activationPermalink);
        }
    }
}
