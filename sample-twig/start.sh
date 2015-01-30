#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/../" && composer install
cd "$DIR" && composer install

cd "$DIR/public" && php -S localhost:8000
