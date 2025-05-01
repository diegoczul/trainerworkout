#!/bin/bash

target_dir="/home/trainerworkout/dev.trainerworkout"
repo_url="git@github.com:luisczul/trainerworkout_v3.git"
branch="main"

# Loop to run the script 3 times with 20 seconds interval
for i in 1 2 3; do

    # Ensure directory exists
    if [ ! -d "$target_dir" ]; then
        mkdir -p "$target_dir"
    fi

    cd "$target_dir" || exit 1

    # Initialize git repo if missing
    if [ ! -d ".git" ]; then
        git init
        git remote add origin "$repo_url"
    fi

    # Set remote in case it changed
    git remote set-url origin "$repo_url"

    # Stash local changes just in case
    git stash --quiet

    # Fetch latest from remote
    git fetch origin "$branch"

    # Hard reset to match origin
    git reset --hard "origin/$branch"

    # Fix permissions
    chown -R www-data:www-data "$target_dir"

    echo "[`date`] Sync attempt $i complete."

    # Wait between iterations
    if [ "$i" -ne 3 ]; then
        sleep 20
    fi

done
