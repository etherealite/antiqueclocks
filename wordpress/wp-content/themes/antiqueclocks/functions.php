<?php
/**
 * Get rid of style.css
 * 
 * it's empty, exists only to make this a child theme of GeneratePress
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'generate-child' );
}, 50 );

function antclks_content_filter( $content ) {
    $post = get_post();
    if ($post->post_type !== 'curios_collectable') {
        return $content;
    }
    $manu = ($terms = get_the_terms($post, 'manufacturer')) ? $terms[0] : false;
    $manu = $manu->name ?? '';
    // $value = get_post_meta( get_the_ID(), 'collectable_sale', true);
    $markup = sprintf( '<p><span style="font-weight: bold">Manufacturer: </span>%s </p>',  esc_html( $manu ) );
    if (pmpro_hasMembershipLevel(1)) {
        $markup .= sprintf('<p>You are a member</p>');
    }
    else {
        $markup .= '<p>You are NOT a member</p>';
    }
    return $markup;
}
add_filter( 'the_content', 'antclks_content_filter' );