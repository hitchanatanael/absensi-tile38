<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Installation
- git clone https://github.com/hitchanatanael/absensi-tile38.git
- go to the project directory
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed DatabaseSeeder
- php artisan serve

Because this project uses Tile38, you need to install tile38 first. For Windows, you can install tile38 here, https://github.com/tidwall/tile38/releases/download/1.33.0/tile38-1.33.0-windows-amd64.zip. For macOS and Linux users, you can enter the documentation from tile38 here https://tile38.com/topics/installation. 

## Run project
- Run tile38 server, tile38-server
