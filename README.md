# myspace

1. Introduction
2. Requirements
3. SetUp
	- Composer
	- Env
	- Migration

## Introduction
Aplikasi sederhana untuk memanagement lokasi (menyimpan lokasi, merubah, hapus) yang menggunakan Maps Javascript API, konsep RESTFULL API, dan JWT sebagai Auth. dan bukan hanya crud, namun terdapat fitur yang bisa memunculkan lokasi2 yang telah tersimpan apabila terjangkau dengan radius dari sebuah lokasi.

## Reguirements
- Laravel 7.5.2
- Jquery 3.5.0
- Maps Javascript API Key
	- Maps Javascript API
	- Directions API
- Bootstrap 4.3.1

## SetUp
- Composer
	- /myspace > composer install / composer update
- Env
	- GMAPS_API={YOUR_MAPS_JAVASCRIPT_API_KEY}
- Migration
	- /myspace > php artisan migrate
