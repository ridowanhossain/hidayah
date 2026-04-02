<?php
require_once('../../../wp-load.php');
$posts = get_posts(array('s' => 'ডেমো বই 7', 'posts_per_page' => 1, 'post_type' => 'any'));
if ($posts) {
    echo "ID: " . $posts[0]->ID . "\n";
    echo "Post Type: " . $posts[0]->post_type . "\n";
} else {
    echo "Post not found\n";
}
