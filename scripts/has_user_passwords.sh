#!/bin/bash


# ________   _______   ________  ___  ___  ________
# |\   ___  \|\  ___ \ |\   __  \|\  \|\  \|\_____  \
# \ \  \\ \  \ \   __/|\ \  \|\  \ \  \\\  \\|___/  /|
# \ \  \\ \  \ \  \_|/_\ \  \\\  \ \  \\\  \   /  / /
# \ \  \\ \  \ \  \_|\ \ \  \\\  \ \  \\\  \ /  /_/__
# \ \__\\ \__\ \_______\ \_____  \ \_______\\________\
# \|__| \|__|\|_______|\|___| \__\|_______|\|_______|
# \|__|

# Project : NequZ - WI
# Created : 16.01.2024
# Author  : NequZ
#
# Copyright (c) 2024 NequZ. All rights reserved.

# This script will hash all passwords in the database that are not already hashed.

DB_USER="nw_user"
DB_PASS="password"
DB_NAME="nw_main"
DB_HOST="localhost"

MYSQL_CMD="mysql -u$DB_USER -p$DB_PASS -h$DB_HOST $DB_NAME"
echo "NequZ WI - Hashing Passwords"
echo "============================"
echo "Hashing passwords for users that are not already hashed..."
echo "============================"

count=0

$MYSQL_CMD -BNe "SELECT id, password FROM nw_users" | while read -r id password; do
    if ! [[ $password =~ ^\$2[ayb]\$.{56}$ ]]; then
        hashed_password=$(php -r "echo password_hash('$password', PASSWORD_DEFAULT);")
        $MYSQL_CMD -e "UPDATE nw_users SET password='$hashed_password' WHERE id=$id"
        echo "Hashed password for user $id"
        ((count++))
    fi
done

echo "============================"
echo "Total passwords hashed: $count"
echo "============================"