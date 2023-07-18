<?php
namespace Custom\Post;
defined('ABSPATH') || die("Direct access not allowed");

class RegisterPost
{
    protected $status;
    protected $allowedRoles;

    public function __construct()
    {
        $this->status = ['publish', 'future', 'draft', 'pending', 'private', 'trash', 'auto-draft', 'inherit'];
        $this->allowedRoles = ['administrator'];
    }

    public function makePost(String $title, String $post_type, array $allowed_role, String $template, array $args, $permalink = null)
    {
        /** Check if title and permalink exist */
        // $post = get_page_by_title($title); // Deprecated since 6.2.0
        $posts = get_posts(
            array(
                'post_type'              => $post_type,
                'title'                  => $title,
                'post_status'            => 'all',
                'numberposts'            => 1,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'orderby'                => 'post_date ID',
                'order'                  => 'ASC',
            )
        );
        $link = $permalink ? get_page_by_path($permalink) : false;

        /** If doesn't exist, create one */
        if (empty($posts) || !$link) {
            /** Check if current user is allowed */
            $currentUser = wp_get_current_user();
            if (in_array(strtolower($currentUser->roles[0]), $allowed_role ?? $this->allowedRoles)) {
                /** Get the args */
                $defaultArguments = self::theArguments($permalink, $title, $post_type, $template);

                if (!empty($args)) {
                    foreach ($args as $key => $value) {
                        $defaultArguments[$key] = $value;
                    }
                }

                $postID = wp_insert_post($defaultArguments, false, true);
                return $postID;
            }
        } else {
            return false;
        }
    }

    protected function theArguments($permalink, String $title, String $post_type, String $template)
    {
        $defaultArguments = [
            'post_title' => $title,
            'post_name' => $permalink ?? '',
            'post_author' => get_current_user_id(),
            'post_date' => date('Y-m-d H:i:s', time()),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
            'post_status' => 'publish',
            'post_type' => $post_type ?? 'post',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_parent' => 0,
            'page_template' => $template ?? 'default'
        ];
        return $defaultArguments;
    }
}
