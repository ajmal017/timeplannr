<?php

use WeDevs\ORM\WP\Post as Post;
use WeDevs\ORM\WP\PostMeta as PostMeta;

class TimeslotModel {

	/**
	 * The books slug name.
	 *
	 * @var string
	 */
	protected $slug = 'timeslot';

	/**
	 * Process one level of the query metadata
     *
     * @param $query Query object
	 * @param array $relationships defines the fields to return and tables to join
	 * @param int $tableCount current number of the joined tables
	 * @param string $joinFieldRight field that used to join the table
	 *
	 * @access public
	 * @static
	 */
	public static function queryLevel($query, $relationships, $tableCount = 0, $joinFieldRight = 'ID')
	{
        $countMeta = $tableCount + 1;
        $parentTableCount = NULL;

        if ($tableCount != 1) {
            if ($tableCount == 0) {
                $parentTableCount = 'wp_posts';
            } else {
                $parentTableCount = 't' . $tableCount;
            }
        }

		foreach ($relationships as $tableName => $relationship) {

			foreach ( $relationship as $linkField => $fields ) {

				foreach ( $fields as $joinField => $fieldOptions ) {

                    if (is_null($parentTableCount)) {
                        $query->join('wp_' . $tableName .' as t' . $countMeta, 't' . $countMeta . '.' . $linkField, '=', 't' . $countMeta . '.' . $joinFieldRight);
                    } else {
                        $query->join('wp_' . $tableName .' as t' . $countMeta, 't' . $countMeta . '.' . $linkField, '=', $parentTableCount . '.' . $joinFieldRight  );
                    }

					if ( is_array( $fieldOptions ) ) {

						$key = array_keys( $fieldOptions )[0];
						$value = array_values( $fieldOptions )[0];

						if (is_numeric($key)) {

							// No need for "AS"
							$query->where('t' . $countMeta . '.meta_value', '=', $value);

						} else {

							if ( is_array( $value )) {

								// Join another table
								self::queryLevel($query, $value, $countMeta, 'meta_value');

							} else {

								// Add to SELECT and WHERE clauses
								$query->addSelect('t' . $countMeta . '.meta_value as ' . $joinField);
								$query->where('t' . $countMeta . '.meta_value', '=', $value);

							}

							// Add to WHERE clause
							$query->where('t' . $countMeta . '.meta_key', '=', $joinField);

						}

					} else {

						// Just a SELECT field
						$query->addSelect('t' . $countMeta . '.meta_value as ' . $fieldOptions);
						$query->where('t' . $countMeta . '.meta_key', '=', is_numeric($joinField) ? $fieldOptions : $joinField);

					}

					$countMeta ++;

				}

			}

		}

		$query->addSelect('wp_posts.ID');

		return $query;

	}

    /**
     * Return a list timeslots for the venue
     *
     * @return array
     */
    public static function perVenue($venueIds)
    {
		$date_2_days_ago = date('Y-m-d', strtotime('-2 days', time()));

	    $query = Post::type('timeslot')
			->select( array('post_title', 'post_status'))
			->where( 'post_status', '=', 'publish' )
			->whereRaw( 'DATE(`t2`.`meta_value`) >= ?', [ $date_2_days_ago ] );

        $relationships = array(
		    'postmeta' => array(
			    'post_id' => array(
				    // 'timeslot_venue' => array('venue_id' => $venueIds),
				    'timeslot_venue',
				    'date',
				    'time_from',
				    'time_to',
				    'title',
				    'timeslot_user',
				    'timeslot_user' => array('timeslot_user' =>
				        array(
					        'usermeta' => array(
						        'user_id' => array(
							        'first_name',
							        'last_name',
						        ),
					        ),
				        ),
				    ),
			    ),
		    ),
	    );

	    $query = self::queryLevel($query, $relationships);

        return $query->get()->toArray();
    }

	public function insert($data, $userId)
	{
		$post = new Post;

		$post->post_title = $data['comments'];
		$post->post_type = $this->slug;
		$post->post_status = 'publish';
		$post->post_author = $userId;
		$post->ping_status = 'closed';

		$temp = array(
			'timeslot_user' => $userId,
			'timeslot_venue' => str_replace('/', '', $data['id']),
			'date' => $data['date'],
			'time_from' => $data['time_from'],
			'time_to' => $data['time_to'],
			'time_to' => $data['time_to'],
			'title' => $data['comments']
		);

		$post->save();

		foreach ($temp as $metaKey => $metaValue) {
			$postmeta = new PostMeta();
			$postmeta->timestamps = FALSE;
			$postmeta->post_id = $post->ID;
			$postmeta->meta_key = $metaKey;
			$postmeta->meta_value = $metaValue;
			$postmeta->save();
		}

		return $post;
	}

	public function getForCurrentDate( $date, $venue_id ) {

		$query = Post::type('timeslot')
			->select( array('post_title', 'post_status'))
			->where( 'post_status', '=', 'publish' )
			->whereRaw( '`t1`.`meta_value` = ?', [ $venue_id ] )
			->whereRaw( 'DATE(`t2`.`meta_value`) = ?', [ $date ] );

		$relationships = array(
			'postmeta' => array(
				'post_id' => array(
					'timeslot_venue',
					'date',
					'timeslot_user',
				),
			),
		);

		$query = self::queryLevel($query, $relationships);

		return $query->get()->toArray();

	}

}