<?php
defined('ABSPATH') || die("Direct access not allowed");

class ContactCPT extends RegisterCPT {
    protected $slugCPT;
    protected $contactMetaKeys;

    public function __construct() {
        $this->slugCPT = 'shop-messages';
        $this->contactMetaKey = [
            '_contact_name' => [
                'label' => 'Your Name',
                'type'  => 'text',
                'placeholder' => 'Please enter your name'
            ],
            '_contact_email' => [
                'label' => 'Your Email Address',
                'type'  => 'email',
                'placeholder' => 'Your Email Address'
            ],
            '_contact_subject' => [
                'label' => 'Subject',
                'type'  => 'text',
                'placeholder' => 'Subject',
            ],
            '_contact_message' => [
                'label' => 'Your Message',
                'type'  => 'textarea',
                'placeholder' => 'Your Message'
            ]
        ];

        add_action('init', [$this, 'contactCreateCPT']);
        add_filter('manage_shop-messages_posts_columns', [$this, 'contactColumn'], 10, 1);
        add_action('manage_shop-messages_posts_custom_column', [$this, 'contactRow'], 10, 2);
    }

    public function contactCreateCPT () {
        $additionalArgs = [
            'menu_posisiton' => 5,
            'has_archive' => true,
            'public' => true,
            'hierarchical' => false,
            'show_in_rest' => true
        ];

        $this->customPostType('Messages', $this->slugCPT, $additionalArgs);
    }

    public function contactColumn ($column_name) {
        unset($column_name['date']);
        $column_name['title']   = __('Subject');
        $column_name['name']    = __('Sender');
        $column_name['email']   = __('Email');
        $column_name['content'] = __('Message');
        $column_name['submit']    = __('Submited On');

        return $column_name;
    }

    public function contactRow ($column_name, $post_id) {
        switch ($column_name) {
            case 'name':
                echo get_post_meta($post_id, '_message_name', true);
                break;
            case 'email':
                echo get_post_meta($post_id, '_message_email', true);
                break;
            case 'content':
                echo get_the_content(null, false, $post_id);
                break;
            case 'submit':
                echo get_the_date( 'Y/m/d H:i:s T', $post_id);
        }
    }
}

// Initiate
new ContactCPT();
