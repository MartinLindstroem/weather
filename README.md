Weather
================
[![Build Status](https://travis-ci.com/MartinLindstroem/weather.svg?branch=master)](https://travis-ci.com/MartinLindstroem/weather)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MartinLindstroem/weather/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MartinLindstroem/weather/?branch=master)

[![Code Coverage](https://scrutinizer-ci.com/g/MartinLindstroem/weather/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/MartinLindstroem/weather/?branch=master)

[![Build Status](https://scrutinizer-ci.com/g/MartinLindstroem/weather/badges/build.png?b=master)](https://scrutinizer-ci.com/g/MartinLindstroem/weather/build-status/master)

This module implements geolocation with weather information. It is intended to be used with the [Anax](https://github.com/canax) framework.

Installation
------------------

### Step 1, install using composer.
Install using composer
`composer require martyzz/weather`

### Step 2, integrate with anax.
Copy necessary files

```
# Go to your anax base repo

# Copy di config file
rsync -av vendor/martyzz/weather/config/di/ip.php config/di/

# Copy routes
rsync -av vendor/martyzz/weather/config/router/ config/router/

# Copy private_keys_sample file and rename it
rsync -av vendor/martyzz/weather/config/private_keys_sample.php config/private_keys.php

# Copy javascript files
rsync -av vendor/martyzz/weather/js/map.js htdocs/js/
rsync -av vendor/martyzz/weather/js/token_sample.js htdocs/js/token.js
```

### Step 3, change values to your own.
* In the file `htdocs/js/token.js`, change value to your own [mapbox](https://www.mapbox.com) API key.
* In the file `config/private_keys.php`change the values to your own [ipstack](https://ipstack.com/) and [openweathermap](https://openweathermap.org/) API keys.

### Step 4, load the js files
In `config/page.php` add `js/token.js` and `js/map.js` like this:
```php
"javascripts" => [
    "js/responsive-menu.js",
    "js/token.js",
    "js/map.js",
],
```

### Step 5, add new routes to navbar.
In `config/navbar/header.php` add the routes to the navbar, for example:
```php
[
    "text" => "Väder",
    "url" => "weather",
    "title" => "Få väderinformation",
],
[
    "text" => "Väder REST-API",
    "url" => "weather-api",
    "title" => "Få väderinformation via REST API",
],
```

### Step 6, add path to views.
In `config/views` add `ANAX_INSTALL_PATH . "/vendor/martyzz/weather/view",` to paths, for example:
```php
"paths" => [
    ANAX_INSTALL_PATH . "/view",
    ANAX_INSTALL_PATH . "/vendor/anax/view/view",
    ANAX_INSTALL_PATH . "/vendor/martyzz/weather/view",
],
```


