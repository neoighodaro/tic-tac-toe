# Sample Tic-tac-toe

This is a sample tic-tac-toe

## Built using
* [Lumen](https://lumen.laravel.com/) - PHP micro-framework
* [Vue.js](https://vuejs.org) - Javascript framework

## Requirements
To run the application you need the following installed on the machine:

* PHP >= 7.1.3
* [Composer](http://getcomposer.org/)
* Modern browser


## Installation
* Download or clone the repository.
* `cd` to the project directory in a terminal and run the command below.
  - `composer install`
* Copy the `.env.example` file in the root directory to `.env`.
* Create a MySQL database, or any other you wish, and add the database credentials to the `.env` file.
* Run the commands below:
  - `php artisan migrate`
  - `php -S localhost:8888 -t public`
* Navigate to http://localhost:8888/game/new on your browser.
