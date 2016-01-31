<?php

/**
 * Class VenueController
 *
 * Controller for routes related to venues
 */

use WeDevs\ORM\WP\Post as Post;

class VenueController extends BaseController
{

	/**
	 * Database connection object
	 *
	 * @var mixed
	 */
	private $_db = NULL;

	public function __construct()
	{
		$this->_db = \WeDevs\ORM\Eloquent\Database::instance();
	}

	public function index()
	{
		return View::make('venues');
	}

	public function find()
	{

		// var_dump(Post::type('venues')->status('publish')->get()->toArray()); // get pages
		// $venues = $this->_db->table('users')->find(1);

		$venues = Post::type('venue')->status('publish')->get()->toArray();

		return View::make('pages.venue.find', array(
			'venues' => $venues
		));
	}

	public function plan()
	{

		if (isset($_POST[Session::nonceName]) && 1 === wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction)) {

			global $current_user;
			get_currentuserinfo();

			$data = Input::all();

			$this->model = new TimeslotModel();
			$this->model->insert($data, $current_user->ID);

		}

		if (!isset($_GET['id'])) {
			$allVenues = VenueModel::all();

			$venuesArray = array();
			foreach ($allVenues as $venue) {
				$venuesArray[$venue['ID']] = $venue;
			};
		} else {
			$venuesArray[$_GET['id']] = VenueModel::details($_GET['id']);
		}

		// Get venue details
		// $venueDetails = VenueModel::details($_GET['id']);

		// dump($venueDetails);

		// Booked slots for the venue
		$bookedSlots = TimeslotModel::perVenue( array_keys($venuesArray) );

		$hours = Config::get('application.timeslots');

		$dates = array(
			'monday' => date("Y-m-d", strtotime('next monday', strtotime('previous sunday'))),
			'tuesday' => date("Y-m-d", strtotime('next tuesday', strtotime('previous sunday'))),
			'wednesday' => date("Y-m-d", strtotime('next wednesday', strtotime('previous sunday'))),
			'thursday' => date("Y-m-d", strtotime('next thursday', strtotime('previous sunday'))),
			'friday' => date("Y-m-d", strtotime('next friday', strtotime('previous sunday'))),
			'saturday' => date("Y-m-d", strtotime('next saturday', strtotime('previous sunday'))),
			'sunday' => date("Y-m-d", strtotime('next sunday', strtotime('previous sunday')))
		);

		return View::make('pages.venue.plan', array(
			'venues' => $venuesArray,
			'booked_slots' => $bookedSlots,
			'dates' => $dates,
			'hours' => Config::get('application.timeslots'),
			'highlight' => ''
		));
	}

	public function register()
	{
		return View::make('pages.venue.register', array());
	}

	public function anton()
	{

		if (isset($_POST[Session::nonceName]) && 1 === wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction)) {

			global $current_user;
			get_currentuserinfo();

			$data = Input::all();

			$this->model = new TimeslotModel();
			$this->model->insert($data, $current_user->ID);

		}

		if (!isset($_GET['id'])) {
			$allVenues = VenueModel::all();

			$venuesArray = array();
			foreach ($allVenues as $venue) {
				$venuesArray[$venue['ID']] = $venue;
			};
		} else {
			$venuesArray[$_GET['id']] = VenueModel::details($_GET['id']);
		}

		// Get venue details
		// $venueDetails = VenueModel::details($_GET['id']);

		// dump($venuesArray);

		// Booked slots for the venue
		$bookedSlots = TimeslotModel::perVenue( array_keys($venuesArray) );

		// dump($bookedSlots);

		$dates = array(
			'monday' => date("Y-m-d", strtotime('next monday', strtotime('previous sunday'))),
			'tuesday' => date("Y-m-d", strtotime('next tuesday', strtotime('previous sunday'))),
			'wednesday' => date("Y-m-d", strtotime('next wednesday', strtotime('previous sunday'))),
			'thursday' => date("Y-m-d", strtotime('next thursday', strtotime('previous sunday'))),
			'friday' => date("Y-m-d", strtotime('next friday', strtotime('previous sunday'))),
			'saturday' => date("Y-m-d", strtotime('next saturday', strtotime('previous sunday'))),
			'sunday' => date("Y-m-d", strtotime('next sunday', strtotime('previous sunday')))
		);

		return View::make('pages.venue.anton', array(
			'venues' => $venuesArray,
			'booked_slots' => $bookedSlots,
			'dates' => $dates,
			'hours' => Themosis\Configuration\Application::get('timeslots'),
			'highlight' => ''
		));

	}

	public function details()
	{
		return View::make('pages.venue.details');
	}

}

?>

