#!/bin/bash

if [[ ! -f "./.env" ]]; then
    echo "[ERROR] You don't have an environment file set up, copy the \`.env.example\` file to \`.env\` and add a random 32 string \`APP_KEY\` value to the file.";
    exit;
fi;

php -S localhost:9000 -t public
