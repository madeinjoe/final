<?php
defined('ABSPATH') || die('Direct access not allowed');

require_once MODULES_DIR . '/post/__register-post.php';

use Custom\Post\RegisterPost as RP;

class ReservationCPT extends RegisterCPT
{
    protected $slugCPT;
    protected $labelCPT;

    public function __construct()
    {
        $this->registerPost = new RP();
        $this->slugCPT = 'reservations';
        $this->labelCPT = 'Reservations';

        add_action('init', [$this, 'createReservationPostType']);
        add_filter('manage_event_posts_columns', [$this, 'listReservationColumn'], 10, 1);
        add_action('manage_event_posts_custom_column', [$this, 'listReservationRow'], 10, 2);

        add_action('after_setup_theme', [$this, 'createReservationPage']);

        // add_action('wp_ajax_reservation_handle', [$this, 'reservationHandle']);
        // add_action('wp_ajax_nopriv_reservation_handle', [$this, 'reservationHandle']);
    }

    public function createReservationPostType()
    {
        $additionalArgs = [
            'menu_position' => 5,
            'has_archive'   => true,
            'public'        => true,
            'hierarchical'  => false,
            'show_in_rest'  => true
        ];

        $this->customPostType($this->labelCPT, $this->slugCPT, $additionalArgs);
    }

    public function listReservationColumn($column_name)
    {
        unset($column_date['date']);
        $column_name['title'] = __('Customer Name');
        $column_name['email'] = __('Customer Email');
        $column_name['phone'] = __('Customer Phone');
        $column_name['reservation_date'] = __('Reservation Date');
        $column_name['date'] = __('Submitted On');
    }

    public function listReservationRow($column_name, $post_id)
    {
        switch ($column_name) {
            case 'title':
                // echo get_the_title(null, false, $post_id);
                echo get_post_meta($post_id, 'reservation_name', true);
                break;
            case 'email':
                echo get_post_meta($post_id, 'reservation_email', true);
                break;
            case 'phone':
                echo get_post_meta($post_id, 'reservation_phone', true);
                break;
            case 'reservation_date':
                echo get_post_meta($post_id, 'reservation_date', true);
                break;
        }
    }

    public function createReservationPage()
    {

        $permalink = 'reservation';
        if (!get_page_by_path($permalink)) {
            $template = 'template-parts/template-reservation.php';
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

            $this->makePost('Make Reservation', 'page', ['administrator'], $template, $args, $permalink);
        }
    }
}

// Initialize
new ReservationCPT();
