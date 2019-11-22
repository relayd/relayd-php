# relayd-php
This repository is a implementation of the Relayd protocol in PHP.

## Installation
To install this implementation of Relayd, follow these steps:
1. Upload this repository to your web server (Apache)
2. Set the root directory of the web server to the `main` directory
3. Setup a MySQL user and database
4. Import the file `core/custom/relayd.sql` into the new database
5. Copy `core/custom/config.example.php` and rename it to `core/custom/config.php`
6. Configure `core/custom/config.php`
   1. Set the host to a subdomain pointed at the Relayd instance
   2. Set the backend name and display names to your liking
7. Setup a `relayd.json` file that points at this instance
   1. Copy `core/custom/relayd.json` to the web root of the domain you want to send from
   2. Adjust the host and allowedSenders to use your Relayd instance
8. Enjoy! You just finished setting up Relayd.