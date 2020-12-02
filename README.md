Weather
================

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
rsync -av vendor/marty/weather/config/di/ip.php config/di/

# Copy routes
rsync -av vendor/marty/weather/config/router/ config/router/

# Copy private_keys_sample file and rename it
rsync -av vendor/marty/weather/config/private_keys_sample.php config/private_keys.php

# Copy javascript files
rsync -av vendor/marty/weather/js/map.js htdocs/js/
rsync -av vendor/marty/weather/js/token_sample.js htdocs/js/token.js

# Copy src files
rsync -av vendor/marty/weather/src/Weather src/Weather

# Copy view files
rsync -av vendor/marty/weather/view/weather view/weather
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

### Step 6, add vendor name to autoloader.
In `composer.json` add `"Marty\\": src/` to the `psr-4` autoloader, like this:
```
"autoload": {
    "psr-4": {
        "Anax\\": "src/",
        "Marty\\": "src/"
    }
},
```

