<?php
/**	
 * 
 */

//Code from on https://code.tutsplus.com/articles/integrating-with-wordpress-ui-admin-pointers--wp-26853

add_action( 'admin_enqueue_scripts', 'wp_ccsearch_pointer_load', 1000 );
	
 
/**
 * 
 * @param type $hook_suffix
 * @return type
 * @since v.0.4.0
 */
function wp_ccsearch_pointer_load( $hook_suffix ) {
 
    $screen = get_current_screen();
    $screen_id = $screen->id;
 
    // Get pointers for this screen
    $pointers = apply_filters( 'wp_ccsearch_admin_pointers-' . $screen_id, array() );
 
    if ( ! $pointers || ! is_array( $pointers ) )
        return;
 
    // Get dismissed pointers
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $valid_pointers =array();
 
    // Check pointers and remove dismissed ones.
    foreach ( $pointers as $pointer_id => $pointer ) {
 
        // Sanity check
        if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
            continue;
 
        $pointer['pointer_id'] = $pointer_id;
 
        // Add the pointer to $valid_pointers array
        $valid_pointers['pointers'][] =  $pointer;
    }
 
	
    // No valid pointers? Stop here.
    if ( empty( $valid_pointers ) ) {
        return;
	}
 
    // Add pointers style to queue.
    wp_enqueue_style( 'wp-pointer' );
 
    // Add pointers script to queue. Add custom script.
    wp_enqueue_script( 'wp_ccsearch-pointer', plugins_url( 'assets/js/wppointer.js', __FILE__ ), array( 'wp-pointer' ) );
 
    // Add pointer options to script.
    wp_localize_script( 'wp_ccsearch-pointer', 'wp_ccsearch_Pointer', $valid_pointers );
}

add_filter( 'wp_ccsearch_admin_pointers-post', 'wp_ccsearch_register_pointer' );
add_filter( 'wp_ccsearch_admin_pointers-page', 'wp_ccsearch_register_pointer' );

/**
 * 
 * @param array $p
 * @return array
 * @since v.0.4.0
 */
function wp_ccsearch_register_pointer( $p ) {
    $p['cs90190'] = array(
		'pointer_id'=>'lswpcc_pointer',
        'target' => '#lswpcc_btn',
        'options' => array(
            'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                __( 'Find an image' ,'ls-wp-ccsearch'),
                __( 'Use it to search millions of free photos then insert an image into content or set as featured image very quickly.','ls-wp-ccsearch')
            ),
            'position' => array( 'edge' => 'left', 'align' => 'left' )
        )
    );
    return $p;
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function wp_ccsearch_block_init() {
	register_block_type(__DIR__ . '/build');
}
add_action('init', 'wp_ccsearch_block_init');