<?php

add_action( 'wp_head', function () {

	$title = get_the_title();
	$desc  = get_field( 'meta_description' );
	$kw    = get_field( 'meta_keywords' );
	$url   = get_permalink();
	$img   = false;

	if ( get_field( 'meta_image' ) ) {
		$img = get_field( 'meta_image_override' );
		if ( $img ) {
			$img = $img['url'];
		}
		else {
			$img = get_the_post_thumbnail_url( null, 'large' );
		}
	}

	if ( $title ) {
		printf( "<meta property=\"og:title\" content=\"%s\" />\n", esc_html($title) );
		printf( "<meta name=\"twitter:title\" content=\"%s\" />\n", esc_html($title) );
	}

	if ( $desc ) {
		printf( "<meta name=\"description\" content=\"%s\" />\n", esc_html($desc) );
		printf( "<meta property=\"og:description\" content=\"%s\" />\n", esc_html($desc) );
		printf( "<meta name=\"twitter:description\" content=\"%s\" />\n", esc_html($desc) );
	}

	if ( $kw ) {
		printf( "<meta name=\"keywords\" content=\"%s\" />\n", esc_html($kw) );
	}

	if ( $url ) {
		printf( "<meta property=\"og:url\" content=\"%s\" />\n", esc_html($url) );
	}

	if ( $img ) {
		printf( "<meta property=\"og:image\" content=\"%s\" />\n", esc_html($img) );
		printf( "<meta name=\"twitter:image\" content=\"%s\" />\n", esc_html($img) );
		print "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
	}

	echo get_field( 'extra_meta_tags' ) . "\n";

} );
