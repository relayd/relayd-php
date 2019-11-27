<?php

$relaydConfig['server'] = array();
// The subdomain pointed at the Relayd instance
$relaydConfig['server']['host'] = "relayd.example.com";
// The backend name for identifying the server
$relaydConfig['server']['backendName'] = "example-relayd";
// The display name for displaying to users
$relaydConfig['server']['displayName'] = "Example";
// The timeout for requests to remote servers
$relaydConfig['server']['requestTimeout'] = 10;

$relaydConfig['drivers'] = array();
// The drivers to use for Relayd
$relaydConfig['drivers']['auth'] = "Relayd_Auth_MySQLi";
$relaydConfig['drivers']['storage'] = "Relayd_Storage_MySQLi";

// The middleware that Relayd uses
$relaydConfig['middleware'] = array(
    "Relayd_Middleware_Example"
);

$relaydConfig['auth'] = array();
// MySQL Configuration for the authentication driver
$relaydConfig['auth']['host'] = "127.0.0.1";
$relaydConfig['auth']['username'] = "mysql-username";
$relaydConfig['auth']['password'] = "mysql-password";
$relaydConfig['auth']['database'] = "mysql-database";

$relaydConfig['storage'] = array();
// MySQL Configuration for the storage driver
$relaydConfig['storage']['host'] = "127.0.0.1";
$relaydConfig['storage']['username'] = "mysql-username";
$relaydConfig['storage']['password'] = "mysql-password";
$relaydConfig['storage']['database'] = "mysql-database";