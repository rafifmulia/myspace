# myspace

1. Introduction
2. Requirements
3. SetUp
	- Composer
	- Env
	- Migration

## Introduction
Aplikasi sederhana yang menggunakan konsep RESTFULL API, API Google Maps dan JWT sebagai Auth

## Reguirements
- Laravel 7.5.2
- Maps Javascript API Key
	- Maps Javascript API
	- Directions API

## SetUp
- Composer
	- /myspace > composer install / composer update
- Env
	-GMAPS_API={YOUR_MAPS_JAVASCRIPT_API_KEY}
- Migration
	- /myspace > php artisan migrate
