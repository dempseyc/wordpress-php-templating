<?php

if ( ! function_exists( 'minimatic_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 */
function minimatic_setup() {

	load_theme_textdomain( 'minimatic' );

    $defaults = array(
        'height'      => 109,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'tagline' ),
    );
    add_theme_support( 'custom-logo', $defaults );

}
endif; // minimatic_setup


add_action( 'after_setup_theme', 'minimatic_setup' );

add_action('thematic_header','the_custom_logo',2);

function use_etsy_feed_template() {
    if ( is_front_page() ) :
        get_template_part('etsyfeed');
    endif;
}

add_action( 'thematic_belowcontent','use_etsy_feed_template');

?>