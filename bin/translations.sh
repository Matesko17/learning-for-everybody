#! /bin/bash
# Script creates and generates translation files in `www/locale` folder

php www/index.php app:translation --create
php www/index.php app:translation --generate
