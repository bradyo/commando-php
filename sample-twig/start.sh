#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR/../" && composer install -o
cd "$DIR" && composer install -o
cd "$DIR/public" && php -S localhost:8000
