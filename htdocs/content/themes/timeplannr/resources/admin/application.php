<?php

/**
 * application.php - Write your custom code below.
 */

require_once('custom_post_types.php');

Custom_Post_Types::set_up();


// Define custom user meta data
User::addFields([
    Field::text('telegram_api_token', ['title' => 'Telegram API token']),
]);
