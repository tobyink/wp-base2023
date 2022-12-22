<?php

namespace App;

function acf_page_id () {
    static $id;
    if ( ! isset($id) ) {
      $id = get_the_ID();
      if ( is_home() ) {
        $id = get_option( 'page_for_posts' );
      }
    }
    return $id;
}

function parsley_nav_menu ( $args ) {
        ob_start();
        wp_nav_menu( $args );
        $html = ob_get_clean();
        return str_replace( 'data-toggle=', 'data-bs-toggle=', $html );
}

function display_sidebar() {
	static $display;
	isset($display) || $display = apply_filters('sage/display_sidebar', false);
	return $display;
}
