<?php

/*
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */


Route::get( 'home', 'VenueController@plan' );

Route::get( 'page', array( 'anton', 'uses' => 'VenueController@anton') );

Route::get( 'page', array( 'venue/find', 'uses' => 'VenueController@find') );
Route::any( 'page', array( 'book', 'uses' => 'VenueController@plan') );
Route::any( 'page', array( array( 'how-to-use', 'contact' ), 'uses' => 'BaseController@page') );

Route::any( 'page', array( 'register', 'uses' => 'VenueController@register') );
Route::get( 'page', array( 'venue/details', 'uses' => 'VenueController@details') );

// Search page
Route::get('search', function() {
	return View::make('search');
});

Route::get('404', function(){

	wp_redirect( '/login' );
	exit;

});