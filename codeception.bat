@echo off
rem Windows/Herd counterpart of ./codeception: runs Codeception with the PHP
rem version Herd serves this site with (respects `herd isolate`).
rem Usage: codeception run [suite] [test] [options]
rem
rem PHP is resolved via `herd which-php` and called directly, because
rem `herd php` swallows the exit code and re-splits quoted arguments.
rem Override the interpreter with: set PHP_BIN=C:\path\to\php.exe
setlocal
cd /d "%~dp0"

if not defined PHP_BIN (
    where herd >nul 2>nul
    if errorlevel 1 (
        echo Laravel Herd not found in PATH. Install Herd or set PHP_BIN. 1>&2
        exit /b 1
    )
    for /f "delims=" %%P in ('herd which-php 2^>nul') do set "PHP_BIN=%%P"
)
if not defined PHP_BIN (
    echo Could not resolve the site's PHP binary via "herd which-php". 1>&2
    exit /b 1
)

if not exist "vendor\bin\codecept" (
    echo vendor\bin\codecept not found. Run "herd composer install" first. 1>&2
    exit /b 1
)

rem Herd's PHP 8.5 CLI ships with register_argc_argv off, which Codeception refuses to run with.
"%PHP_BIN%" -d register_argc_argv=On vendor\bin\codecept %*
exit /b %ERRORLEVEL%
