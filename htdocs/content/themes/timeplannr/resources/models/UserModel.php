<?php

class UserModel {

    /**
     * Return a list users.
     *
     * @return array
     */
    public static function all()
    {
        return get_users();
    }

    public static function userSelection()
    {
        $users = array();
        foreach(static::all() as $user)
        {
            $user_data = get_userdata($user->ID);
            $users[$user->ID] = $user_data->first_name . '  ' . $user_data->last_name;
        }
        return $users;
    }

} 