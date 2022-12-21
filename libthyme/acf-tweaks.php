<?php

add_action( 'acf/init', function () {
	acf_update_setting( 'google_api_key', 'AIzaSyByf6BkXuinyB9hp35TyJ7B6yssf3fZ2Os' );
} );

if ( ! is_admin() ) {

	add_filter( 'acf/validate_form', function ($args) {
		if($args['html_updated_message'] == '<div id="message" class="updated"><p>%s</p></div>')
			$args['html_updated_message'] = '<div id="message" class="updated alert alert-success">%s</div>';
		if($args['html_submit_button'] == '<input type="submit" class="acf-button button button-primary button-large" value="%s" />')
			$args['html_submit_button'] = '<input type="submit" class="acf-button button button-primary button-large btn btn-primary" value="%s" />';
		return $args;
	} );

	add_filter( 'acf/prepare_field', function ($field) {
		if(is_admin() && !wp_doing_ajax())
			return $field;
		$field['wrapper']['class'] .= ' form-group';
		if ( in_array( $field['type'], [ 'text', 'textarea', 'select', 'email', 'number' ] ) ) {
			$field['class'] .= ' form-control';
		}
		return $field;
	} );

	add_filter( 'acf/get_field_label', function ($label) {
		if(is_admin() && !wp_doing_ajax())
			return $label;
		$label = str_replace('<span class="acf-required">*</span>', '<span class="acf-required text-danger">*</span>', $label);
		return $label;
	} );
}
