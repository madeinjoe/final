<?php
defined('ABSPATH') || die('Direct access not allowed');

class CustomShortcode
{
    public function __construct()
    {
        add_shortcode('testshortcode', [$this, 'showElement']);
    }

    public function showElement($attributes = [], $content = '')
    {
        $productIDs = is_array($attributes) ? explode(',', $attributes['products']) : [];
        $categoryIDs = is_array($attributes) ? explode(',', $attributes['categories']) : [];

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'post__not_in' => $productIDs,
            'orderby' => 'rand',
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $categoryIDs,
                    'compare' => 'IN'
                ]
            ]
        );

        // $query = new WP_Query($args);
        $query = get_posts($args);

        $content .= '<ul class="cart-collaterals-left">';
        // if ($query->have_posts()) {
        //     while ($query->have_posts()) {
        //         $query->the_post();

        //         $content .= '<div class="cart-collaterals-card">';
        //         $content .= '<a href="' . get_permalink() . '" class="">';
        //         $content .= '<div class="cart-collaterals-img">' . woocommerce_get_product_thumbnail() . '</div>';
        //         $content .= '</a>';
        //         $content .= '<a href="' . get_permalink() . '" class="">';
        //         $content .= '<h2>' . get_the_title() . '</h2>';
        //         $content .= '</a>';
        //         $content .= '</div>';
        //     }
        // }

        foreach ($query as $eachPost) {
            setup_postdata($eachPost);

            $content .= '<div class="cart-collaterals-card">';
            $content .= '<a href="' . $eachPost->guid . '" class="">';
            $content .= '<div class="cart-collaterals-img">' . woocommerce_get_product_thumbnail() . '</div>';
            $content .= '</a>';
            $content .= '<a href="' . $eachPost->guid . '" class="">';
            $content .= '<h2>' . $eachPost->post_title . '</h2>';
            $content .= '</a>';
            $content .= '</div>';
        }

        $content .= '</ul>';

        wp_reset_postdata();
        return $content;
    }
}

new CustomShortcode();
