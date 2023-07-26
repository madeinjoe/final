<?php
defined('ABSPATH') || die('Direct access not allowed');

class EventCPT extends RegisterCPT
{
    protected $slugCPT;
    protected $labelCPT;

    public function __construct()
    {
        $this->slugCPT = 'events';
        $this->labelCPT = 'Events';

        add_action('init', [$this, 'createEventPostType']);
        add_filter('manage_event_posts_columns', [$this, 'listEventColumn'], 10, 1);
        add_action('manage_event_posts_custom_column', [$this, 'listEventRow'], 10, 2);
    }

    public function createEventPostType()
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

    public function listEventColumn($column_name)
    {
        unset($column_date['date']);
        $column_name['title'] = __('Event');
        $column_name['content'] = __('Description');
        $column_name['event_date'] = __('Event Date');
    }

    public function listEventRow($column_name, $post_id)
    {
        switch ($column_name) {
            case 'title':
                echo get_the_title(null, false, $post_id);
                break;
            case 'content':
                echo get_the_content(null, false, $post_id);
                break;
            case 'event_date':
                $start = get_post_meta($post_id, '_event_date_begin', true);
                $end = get_post_meta($post_id, '_event_date_end', true);
                if ($start === $end) {
                    echo $start;
                } else {
                    echo $start . ' - ' . $end;
                }
                break;
        }
    }
}

// Initialize
new EventCPT();
