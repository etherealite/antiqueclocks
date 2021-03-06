<?php

/** 
 * null out install defaults 
 * 
 * Prevents Wordpress from installing sample content on a 
 * new install by overiding the core function `wp_install_defaults` This 
 * will install the required minimum of having a default category in order 
 * to have a working site. 
*/
function wp_install_defaults() {
    global $wpdb;
    // Default category
    $cat_name = $wpdb->escape(__('Uncategorized'));
    $cat_slug = sanitize_title(_c('Uncategorized|Default category slug'));
    $wpdb->query("INSERT INTO $wpdb->terms (name, slug, term_group) VALUES ('$cat_name', '$cat_slug', '0')");
    $wpdb->query("INSERT INTO $wpdb->term_taxonomy (term_id, taxonomy, description, parent, count) VALUES ('1', 'category', '', '0', '1')");
}
