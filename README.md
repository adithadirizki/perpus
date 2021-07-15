# CodeIgniter 4 Framework

## How to install?

`git clone https://github.com/adithadirizki/perpus`
- Set .env
- Set your baseURL in 'app\Config\App.php'
- Set email configure in 'app\Config\Email.php'
- Create database and import db_perpus.sql
- Run your server
- Login with : `-username = admin@perpus.com` &  `-password = admin123`

## Server Requirements

PHP version 7.2 or higher is required, with the following extensions installed: 

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
