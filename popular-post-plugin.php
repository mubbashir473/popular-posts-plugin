<?php
/*
Plugin Name: Popular Posts Plugin
Description: Display the most popular posts based on view count (Neeeds Post View Counter plugiin Activated).
Version: 1.0
Author: Mubbashir Hassan
Text Domain: popular-posts-plugin
*/

// Query popular posts
function popular_posts($number = 5) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $number,
        'meta_key'       => 'pvc_views',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    return new WP_Query($args);
}

// Shortcode to display popular posts
function popular_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'number' => 5
    ), $atts, 'popular_posts');

    $query = popular_posts($atts['number']);
    $output = '<ul class="ppp-popular-posts">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $views = get_post_meta(get_the_ID(), 'pvc_views', true);
            $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . $views . ' views)</li>';
        }
    } else {
        $output .= '<li>No popular posts found.</li>';
    }

    wp_reset_postdata();
    $output .= '</ul>';

    return $output;
}
add_shortcode('popular_posts', 'popular_posts_shortcode');
