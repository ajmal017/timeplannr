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
		'7.5' => '7:30am',
		'8' => '8am',
		'8.5' => '8:30am',
		'9' => '9am',
		'9.5' => '9:30am',
		'10' => '10am',
		'10.5' => '10:30am',
		'11' => '11am',
		'11.5' => '11:30am',
		'12' => '12pm',
		'12.5' => '12:30pm',
		'13' => '1pm',
		'13.5' => '1:30pm',
		'14'=> '2pm',
		'14.5'=> '2:30pm',
		'15' => '3pm',
		'15.5' => '3:30pm',
		'16' => '4pm',
		'16.5' => '4:30pm',
		'17' => '5pm',
		'17.5' => '5:30pm',
		'18' => '6pm',
		'18.5' => '6:30pm',
		'19' => '7pm',
		'19.5' => '7:30pm',
		'20' => '8pm',
		'20.5' => '8:30pm',
		'21' => '9pm',
		'21.5' => '9:30pm',
		'22' => '10pm',
		'22.5' => '10:30pm',
		'23' => '11pm',
		'23.5' => '11:30pm'
	),

];