<?php

use WeDevs\ORM\WP\Post as Post;
use WeDevs\ORM\WP\PostMeta as PostMeta;


class VenueModel {

    /**
     * The books slug name.
     *
     * @var string
     */
    protected $slug = 'venue';

    /**
     * Return a list of all published venues.
     *
     * @param string $filter
     *  Allows to filter the venues by name
     * @return array
     */
    public static function all( $filter = NULL )
    {
        $query = Post::type('venue')
            ->status('publish')
            ->where('post_title', 'like', "%$filter%")
            ->orderBy('post_title');

        // Joining the post meta to filter by the "active" field
        $query->join('wp_postmeta as m_a', 'm_a.post_id', '=', 'wp_posts.ID');
        $query->where('m_a.meta_key', '=', 'active');
        $query->where('m_a.meta_value', '=', '1');

        // Joining the post meta to filter by the "confirmed" field
        $query->join('wp_postmeta as m_c', 'm_c.post_id', '=', 'wp_posts.ID');
        $query->where('m_c.meta_key', '=', 'confirmed');
        $query->where('m_c.meta_value', '=', '1');

        $venues = $query->get()->toArray();

        return $venues;

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

    /**
     * Get venue details
     *
     * @param int $venueId
     * @return array
     */
    public function add_new( $data, $current_user )
    {
        $result = $this->insert( $data, $current_user );
        return $result;
    }

    public function insert( $data, $userId )
    {

        $post = new Post;

        $post->post_title = $data['title_a'];
        $post->post_name = sanitize_title_with_dashes( $data['title_a'] );
        $post->post_type = $this->slug;
        $post->post_status = 'publish';
        $post->post_author = $userId;
        $post->ping_status = 'closed';

        $temp = array(
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postcode' => $data['postcode'],
            'country' => $data['country'],

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

}