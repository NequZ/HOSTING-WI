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
# Created : 18.01.2024
# Author  : NequZ
#
# Copyright (c) 2024 NequZ. All rights reserved.

# This script is responsible for sending the current server infos to the database like cpu usage, ram usage, disk usage, etc.
# sysstat and bc packages are required for this script to work properly.
DB_USER="nw_user"
DB_PASS="password"
DB_NAME="nw_main"
DB_HOST="localhost"
SERVER_ID_FILE="/home/hosting_wi_agent_serverid.txt"

if [ -s "$SERVER_ID_FILE" ]; then
    SERVER_ID=$(cat "$SERVER_ID_FILE")
else
    read -p "Welcome to the Hosting-WI-Agent. This Agent sends the current Server Infos to the Database. On the first run you need to enter the ServerID from the Database nw_server table. Please enter the ServerID: " SERVER_ID
    echo "$SERVER_ID" > "$SERVER_ID_FILE"
fi
DISK_USAGE=$(df -h | awk '$NF=="/"{printf "%s", $5}')
CPU_USAGE=$(mpstat 1 1 | awk '/Average:/ {print 100 - $12}')
RAM_USAGE=$(free -m | awk 'NR==2{printf "%.2f%%", $3*100/$2 }')

MYSQL_CMD="mysql -u$DB_USER -p$DB_PASS -h$DB_HOST $DB_NAME"
echo "NequZ WI - Agent"
echo "============================"
echo "Sending server infos to database..."
echo "============================"
echo "CPU Usage: $CPU_USAGE"
echo "RAM Usage: $RAM_USAGE"
echo "Disk Usage: $DISK_USAGE"
echo "============================"
function updateServerUsage {
    read usable_memory usable_cpu usable_disk <<< $(echo "SELECT usable_memory, usable_cpu, usable_disk FROM nw_server_details WHERE serverid='$SERVER_ID';" | $MYSQL_CMD -Bs)

    if [ -z "$usable_memory" ] || [ -z "$usable_cpu" ] || [ -z "$usable_disk" ]; then
        echo "Server details not found for ID: $SERVER_ID"
        return 1
    fi

    parsed_disk_usage=${DISK_USAGE%\%}
    parsed_cpu_usage=${CPU_USAGE%\%}
    parsed_ram_usage=${RAM_USAGE%\%}

    current_used_memory=$(echo "scale=2; $usable_memory * $parsed_ram_usage / 100" | bc)
    current_used_cpu=$(echo "scale=2; $usable_cpu * $parsed_cpu_usage / 100" | bc)
    current_used_disk=$(echo "scale=2; $usable_disk * $parsed_disk_usage / 100" | bc)
    update_query="UPDATE nw_server_details SET current_used_memory='$current_used_memory', current_used_cpu='$current_used_cpu', current_used_disk='$current_used_disk', online=1 WHERE serverid='$SERVER_ID';"
    echo "$update_query" | $MYSQL_CMD

    echo "Server usage updated successfully."
}

updateServerUsage