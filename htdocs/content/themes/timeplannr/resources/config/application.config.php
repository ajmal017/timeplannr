<?php

return [

	/*
	* Edit this file in order to configure your application
	* settings or preferences.
	* 
	*/

	/* --------------------------------------------------------------- */
	// Application textdomain
	/* --------------------------------------------------------------- */
	'textdomain'    => 'themosis',

	/* --------------------------------------------------------------- */
	// Global Javascript namespace of your application
	/* --------------------------------------------------------------- */
	'namespace'     => 'themosis',

	/* --------------------------------------------------------------- */
	// Set WordPress admin ajax file without the PHP extension
	/* --------------------------------------------------------------- */
	'ajaxurl'	    => 'admin-ajax',

	/* --------------------------------------------------------------- */
	// Cleanup Header
	/* --------------------------------------------------------------- */
	'cleanup'	    => true,

	/* --------------------------------------------------------------- */
	// Restrict access to the WordPress Admin for users with a
	// specific role. 
	// Once the theme is activated, you can only log in by going
	// to 'wp-login.php' or 'login' (if permalinks changed) urls.
	// By default, allows 'administrator', 'editor', 'author',
	// 'contributor' and 'subscriber' to access the ADMIN area.
	// Edit this configuration in order to limit access.
	/* --------------------------------------------------------------- */
	'access'	    => [
		'administrator',
		'editor',
		'author',
		'contributor',
		'subscriber'
    ],

	/* --------------------------------------------------------------- */
	// Theme class aliases
	/* --------------------------------------------------------------- */
	'aliases'	    => [],

	'timeslots'     => array(
		'7' => '7am',
		'8' => '8am',
		'9' => '9am',
		'10' => '10am',
		'11' => '11am',
		'12' => '12pm',
		'13' => '1pm',
		'14'=> '2pm',
		'15' => '3pm',
		'16' => '4pm',
		'17' => '5pm',
		'18' => '6pm',
		'19' => '7pm',
		'20' => '8pm',
		'21' => '9pm',
		'22' => '10pm',
		'23' => '11pm'
	),

];