<?php

/*
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */


Route::get('home', 'VenueController@plan');

Route::get('page', array('anton', 'uses' => 'VenueController@anton'));

Route::get('page', array('venue/find', 'uses' => 'VenueController@find'));
Route::any('page', array('book', 'uses' => 'VenueController@plan'));
Route::any('page', array('register', 'uses' => 'VenueController@register'));
Route::get('page', array('venue/details', 'uses' => 'VenueController@details'));

// Search page
Route::get('search', function() {
	return View::make('search');
});

Route::get('404', function(){

	wp_redirect( '/login' );
	exit;

});


/*

Route::get('home', function(){

    return View::make('welcome');

});

Route::get('page', array('anton', function(){

	return View::make('anton', array('name' => 'Anton'));

}));

Route::get('page', array('venues', function(){

	return View::make('anton', array('name' => 'Anton'));

}));

*/