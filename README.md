About Timeplannr
================

The Timeplannr app is an easy and visual way to communicate when friends about meeting up for any reason at any venue registered with Timeplannr. 

Pick the venue, tap on the timeslot and set how long you are going to stay at the select venue. Your booking will be visible to all the Timeplannr users. No need to sms everyone to organise the meet up.

Timeplannr is using WordPress as a content management system and Themosis as a web development framework.

Third-party components
----------------------
A few third-party components are used in Timeplannr

- **WP-Eloquent** - an Eloquent Wrapper for WordPress written by tareq1988 (https://github.com/tareq1988/wp-eloquent)

Installation
------------

Follow the steps below to install Timeplannr in your own environment:

- git clone https://github.com/xeiter/timeplannr.git .
- composer install
- run "hostname" command and note the value of the hostname
- insert the hostname  into  the "/config/environment.php" (replace "your-environment-name" with your environment name)
```
return [
    'your-environment-name'    => 'zaroutski.com'
];
```
- Create a "/.env.<your-environment-name>" file and place your settings into it:
```
<?php

/*----------------------------------------------------*/
// Local environment vars
/*----------------------------------------------------*/
return [
    'DB_NAME'       => 'timeplannr',
    'DB_USER'       => 'your-database-username',
    'DB_PASSWORD'   => 'your-database-password',
    'DB_HOST'       => 'hostname',
    'WP_HOME'       => 'http://domain.com',
    'WP_SITEURL'    => 'http://domain.com/cms'
];
```
- Create a directory "<timeplannr-directory>/storage" and "<timeplannr-directory>/storage/views"
- Make sure that "<timeplannr-directory>/storage/views" has write permissions so cache files can be written by the webserver
- Activate your theme in WordPress admin
- Delete the default Themosis theme (if exists)
- In /vendor/tareq1988/wp-eloquent/src rename the following:
    - "eloquent" to "Eloquent"
    - "wp" to "WP"
- Make sure permissions for "/htdocs/.htaccess" file are writable for the webserver  
  
Contributing
------------
Any help is appreciated. The project is open-source and we encourage you to participate. You can contribute to the project in multiple ways by:

- Reporting a bug issue
- Suggesting features
- Sending a pull request with code fix or feature
- Following the project on GitHub
- Following us on Twitter
- Sharing the project around your community

Thank you,
Anton
