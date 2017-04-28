# Pinboard public copy

This syncs up your bookmarks from [Pinboard](https://pinboard.in) into a local database, and allows you to do whatever you want from this cached databased — for me, it's just to show what I'm reading, but while setting my own style on it. Might be a potential starting point for experimenting with visualisations of your bookmarks.

**This is an unfinished work in progress for now.**

## Installation

This is built on top of Laravel. To install you just need to already have PHP 7 and Composer.

- Clone this repository somewhere
- `composer install` should download the framework and libraries for you
- Copy `.env.example.php` into `.env` and modify the database details and your Pinboard username & token (what's after the colon)
- `php artisan migrate:install && php artisan migrate:refresh` to set up your database
- Get a server to point to the public/ directory (follow Laravel instructions depending on your server)
- It should work!
