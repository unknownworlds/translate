# Translation Interface
Super-duper community-powered translations. Used for [Unknown Worlds Community Translations](http://translate.unknownworlds.com/).
Based on Laravel.

## Usage examples
- [Red Hook Studios - Darkest Dungeon](http://www.translate.darkestdungeon.com/)
- [New World Interactive - Insurgency, Day of Infamy](https://translate.newworldinteractive.com/)

## Base workflow example
- Store your source text in English.json file
- When the game is built, upload the source file to the app - it gets processed: strings get added, updated or deleted
- Right after download the latest translations from the app - it's going to be a zip file containing all the data you need
- Check in those files into your repository and proceed with the build process 

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

## Worth knowing
- You can't use numbers as translation keys

## Meta
Created by [Unknown Worlds Entertainment's](http://unknownworlds.com/) Lukas Nowaczek ([@lnowaczek](https://twitter.com/lnowaczek)).

Distributed under the MIT license. See ``LICENSE`` for more information.
