# Translation Interface
Super-duper community-powered translations. Used for [Unknown Worlds Community Translations](http://translate.unknownworlds.com/).
Based on Laravel.

## Requirements
- PHP >= 5.6.4
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation instructions
- Point web server's root directory to /public
- Copy .env.testing to .env, fill the configuration
- Run "composer install" from the root directory of the application
- Run "php artisan key:generate" to set unique crypto key

## Development environment
Use [Laravel Homestead](https://laravel.com/docs/master/homestead)

## Testing
./codeception run (works under linux)

## Meta
Created by [Unknown Worlds Entertainment's](http://unknownworlds.com/) Lukas Nowaczek ([@lnowaczek](https://twitter.com/lnowaczek)).

Distributed under the MIT license. See ``LICENSE`` for more information.
