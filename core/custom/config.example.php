<?php

$relaydConfig['server'] = array();
$relaydConfig['server']['host'] = "relayd.example.com";
$relaydConfig['server']['backendName'] = "example-relayd";
$relaydConfig['server']['displayName'] = "Example";
$relaydConfig['server']['requestTimeout'] = 10;

$relaydConfig['drivers'] = array();
$relaydConfig['drivers']['auth'] = "Relayd_Auth_MySQLi";
$relaydConfig['drivers']['storage'] = "Relayd_Storage_MySQLi";

$relaydConfig['auth'] = array();
$relaydConfig['auth']['host'] = "127.0.0.1";
$relaydConfig['auth']['username'] = "mysql-username";
$relaydConfig['auth']['password'] = "mysql-password";
$relaydConfig['auth']['database'] = "mysql-database";

$relaydConfig['storage'] = array();
$relaydConfig['storage']['host'] = "127.0.0.1";
$relaydConfig['storage']['username'] = "mysql-username";
$relaydConfig['storage']['password'] = "mysql-password";
$relaydConfig['storage']['database'] = "mysql-database";