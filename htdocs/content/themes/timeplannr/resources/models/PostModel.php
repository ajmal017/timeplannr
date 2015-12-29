<?php

class PostModel
{
    /**
     * Return a list of all published posts.
     *
     * @return array
     */
    public function all()
    {
        $query = new WP_Query([
            'post_type'         => 'post',
            'posts_per_page'    => -1,
            'post_status'       => 'publish'
        ]);

        return $query->get_posts();
    }
}