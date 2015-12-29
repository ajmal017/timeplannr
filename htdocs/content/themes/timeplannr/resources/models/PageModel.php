<?php

class PageModel {
    /**
     * Return a list of all published posts.
     *
     * @return array
     */
    public static function all()
    {
        $query = new WP_Query(array(
            'post_type'         => 'page',
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
    public static function pageSelection()
    {
        $pages = array();
        foreach(static::all() as $page)
        {
            $pages[$page->ID] = ucfirst($page->post_title);
        }
        return $pages;
    }
}