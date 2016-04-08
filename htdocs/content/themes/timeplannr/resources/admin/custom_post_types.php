<?php

class Custom_Post_Types {

	public function __construct() {}

	/**
	 * Prepare all the custom post types
	 */
	public static function set_up() {

		self::_set_up_venues();
		self::_set_up_timeslot();

	}

	/**
	 * Set up custom post type for venues
	 *
	 * @private
	 */
	private static function _set_up_venues() {

		// Register Venues custom post types
		PostType::make('venue', 'Venues', 'Venue')->set();

		// Define fields for the metabox
		$fields = array(
			Field::checkbox('active', array('1' => 'Yes')),
			Field::checkbox('confirmed', array('1' => 'Yes')),
			Field::text('address', array('title' =>'Address')),
			Field::text('city', array('title' =>'City')),
			Field::text('state', array('title' =>'State')),
			Field::text('postcode', array('title' =>'Postcode')),
			Field::text('country', array('title' =>'Country')),
		);

		// Add metabox with custom fields
		Metabox::make('Venue details', 'venue')->set($fields);

		// Add venue types custom taxonomy
		Taxonomy::make('venue_type', 'venue', 'Venue Types', 'Venue Type')->set();

	}

	/**
	 * Set up timeslot custom post type
	 *
	 * @private
	 */
	private static function _set_up_timeslot() {

		PostType::make('timeslot', 'Time Slots', 'Time Slot')->set();

		$fields = array(

			Field::select('timeslot_venue', array(
				array(
					'none' => __('- None -'),
				) + VenueModel::venueSelection()), array(
				'title'     => __('Venue')
			)),

			Field::select('timeslot_user', array(
				array(
					'none' => __('- None -'),
				) + UserModel::userSelection()), array(
				'title'     => __('User')
			)),

			Field::date('date', array('title' =>'Date')),

			Field::select('time_from', array(
				Config::get('application.timeslots')), array(
				'title'     => __('Start at')
			)),

			Field::select('time_to', array(
				Config::get('application.timeslots')), array(
				'title'     => __('Finish at')
			)),

		);

		Metabox::make('Options', 'timeslot')->set($fields);

	}

}





