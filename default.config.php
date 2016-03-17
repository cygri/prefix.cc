<?php

error_reporting(E_ALL);
$config = array(
    // Without protocol, to allow for either http or https
    'site_base' => '//prefixcc.local/',  // for local development
//    'site_base' => '//prefix.cc/',   // for production
    'block_time' => 60, // seconds -- this is for local development
//    'block_time' => 60 * 60 * 16, // seconds -- this is for production
    'db_host' => 'localhost',
    'db_name' => 'prefixcc',
    'db_user' => 'root',
    'db_password' => '',
);
