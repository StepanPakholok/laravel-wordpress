<?php

/*
 * Helper to use within the Laravel to generate Laravel links under the the plugin 'hood' in Laravel manner
 * e.g. somewhere in view:
 *  <a href="{{ link_to('/users') }}">Users</a>
 */
function link_to( $uri, $vars = [] ) {
	$url = 'admin.php?page=laravel_wordpress&path=' . urlencode( '/' . $uri );
	foreach ( $vars as $name => $value ) {
		$url .= '&' . $name . '=' . urlencode( $value );
	}

	return admin_url( $url );
}

/*
 * Helper to generate Laravel links to be caught by plugin's AJAX callback function laravel_cms_ajax_callback()
 * e.g. somewhere in view:
 *  jQuery.get("{{ ajax_link_to('/users') }}", {
 *        action:   'laravel_wordpress_ajax',
 *        whatever: 1111
 *  }, function(response) {
 *      //
 *  });
 */
function ajax_link_to( $uri, $vars = [] ) {

	$url = 'admin-ajax.php?path=' . urlencode( '/' . $uri );
	foreach ( $vars as $name => $value ) {
		$url .= '&' . $name . '=' . urlencode( $value );
	}

	return admin_url( $url );
}
