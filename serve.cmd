@echo off
REM Run the Laravel dev server using PHP 8.2 (required by Laravel 12).
REM WAMP's default `php` on this machine is 7.4, so we call 8.2 explicitly.
REM Port 8080 is used because 8000 is taken by another local project.
"C:\wamp\bin\php\php8.2.0\php.exe" "%~dp0artisan" serve --host=127.0.0.1 --port=8080
