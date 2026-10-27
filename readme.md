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
- PHP >= 8
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Zip PHP Extension

## Installation instructions
- Point web server's root directory to /public
- Copy .env.testing to .env, fill the configuration
- Run "composer install" from the root directory of the application
- Run "php artisan key:generate" to set unique crypto key

## Development environment
Use [Laravel Herd](https://herd.laravel.com/) (Pro, for the MySQL service)
- Link the site as `uwetranslate.test` and isolate it to the PHP version it should run on (`herd isolate 8.5`)
- Create the testing database: `herd db:create uwetranslate-testing`
- Point `.env.testing` at Herd's MySQL (`DB_HOST=127.0.0.1`, `DB_PORT=3307`, `DB_USERNAME=root`, empty password) - Codeception reads its DB settings from it too

## Testing
- `./codeception run` (Linux/macOS)
- `codeception run` (Windows/Herd - uses the PHP version Herd serves the site with)

## Worth knowing
- You can't use numbers as translation keys

## Meta
Created by [Unknown Worlds Entertainment's](http://unknownworlds.com/) Lukas Nowaczek ([@lnowaczek](https://twitter.com/lnowaczek)).

Distributed under the MIT license. See ``LICENSE`` for more information.
