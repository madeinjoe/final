<?php
defined('ABSPATH') || die('Direct access not allowed');

class CloseDateCPT extends RegisterCPT
{
    protected $slugCPT;
    protected $labelCPT;

    public function __construct()
    {
        $this->slugCPT = 'close-date';
        $this->labelCPT = 'Close Date';

        add_action('init', [$this, 'createCloseDateType']);
        add_filter('manage_event_posts_columns', [$this, 'listCloseDateColumn'], 10, 1);
        add_action('manage_event_posts_custom_column', [$this, 'listCloseDateRow'], 10, 2);
    }

    public function createCloseDateType()
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

    public function listCloseDateColumn($column_name)
    {
        unset($column_date['date']);
        $column_name['title'] = __('Event');
        $column_name['content'] = __('Description');
        $column_name['close_date'] = __('Event Date');
    }

    public function listCloseDateRow($column_name, $post_id)
    {
        switch ($column_name) {
            case 'title':
                echo get_the_title(null, false, $post_id);
                break;
            case 'content':
                echo get_the_content(null, false, $post_id);
                break;
            case 'close_date':
                $start = get_post_meta($post_id, '_close_date_begin', true);
                $end = get_post_meta($post_id, '_close_date_end', true);
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
new CloseDateCPT();
