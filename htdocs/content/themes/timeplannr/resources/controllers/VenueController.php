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
			$result = $this->model->insert($data, $current_user->ID);

			echo json_encode( $result );
			exit;
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

		$posts = get_posts(
			array(
				'post_type' => 'timeslot',
				'posts_per_page' => -1
			)
		);

		$posts_for_select = array();
		foreach( $posts as $post ) {
			$posts_for_select[ $post->ID ] = get_the_title( $post->ID ) ? get_the_title( $post->ID ) . ' - POST #' . $post->ID : 'N/A - POST #' . $post->ID;
		}

		return View::make('pages.venue.anton', array(
			'posts' => $posts_for_select
		));

	}

	public function details()
	{
		return View::make('pages.venue.details');
	}

	public function suggest() {

		$submitted = FALSE;

		if (isset($_POST[Session::nonceName]) && 1 === wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction)) {

			global $current_user;
			get_currentuserinfo();

			$data = Input::all();

			$this->model = new VenueModel();
			$result = $this->model->add_new( $data, $current_user->ID );

			$email_notification_body  = 'Title: ' . $data['title_a'] . "\n";
			$email_notification_body .= 'Address: ' . $data['address'] . "\n";
			$email_notification_body .= 'City: ' . $data['city'] . "\n";
			$email_notification_body .= 'State: ' . $data['state'] . "\n";
			$email_notification_body .= 'Postcode: ' . $data['postcode'] . "\n";
			$email_notification_body .= 'Country: ' . $data['country'] . "\n";

			wp_mail( get_option('admin_email'), __( 'Timeplannr - New venue suggestion ("' . $data['title_a'] . '")', 'timeplannr' ), $email_notification_body );

			$submitted = TRUE;

		}

		// Render the view
		return View::make('pages.venue.suggest', array(
			'submitted' => $submitted
		));

	}

}

?>
