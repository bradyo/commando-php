#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/../" && composer install
cd "$DIR" && composer install

if [[ ! -f "$DIR/config/config.php" ]]; then
    cp "$DIR/config/config-default.php" "$DIR/config/config.php"
fi

cd "$DIR/public" && php -S localhost:8000