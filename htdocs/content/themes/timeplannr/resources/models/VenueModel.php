<?php

use WeDevs\ORM\WP\Post as Post;

class VenueModel {

    /**
     * The books slug name.
     *
     * @var string
     */
    protected $slug = 'venues';

    /**
     * Return a list of all published venues.
     *
     * @param string $filter
     *  Allows to filter the venues by name
     * @return array
     */
    public static function all( $filter = NULL )
    {
        $venue = Post::type('venue')->status('publish')->where('post_title', 'like', "%$filter%")->get()->toArray();

        return $venue;

        $query = new WP_Query(array(
            'post_type'         => 'venue',
            'posts_per_page'    => -1,
            'post_status'       => 'publish'
        ));

        return $query->get_posts();

    }
    /**
     * Return an array with the page ID and page title.
     *
     * @return array
     */
    public static function venueSelection()
    {
        $pages = array();
        foreach(static::all() as $page)
        {
            $pages[$page['ID']] = ucfirst($page['post_title']);
        }
        return $pages;
    }

    /**
     * Get venue details
     *
     * @param int $venueId
     * @return array
     */
    public static function details($venueId)
    {
        $venue = Post::type('venue')->status('publish')->whereId($venueId)->get()->first();

        if ($venue) {
            return $venue->toArray();
        }

        return NULL;
    }

}