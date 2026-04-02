<?php
require_once('../../../wp-load.php');
$books = count(get_posts(array('post_type' => 'book', 'posts_per_page' => -1)));
$products = count(get_posts(array('post_type' => 'product', 'posts_per_page' => -1)));
echo "Books: $books\nProducts: $products\n";
