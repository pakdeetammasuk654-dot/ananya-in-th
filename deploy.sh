#!/bin/bash

# ==============================================================================
# Deployment Script for Go Numerology API
# ==============================================================================
#
# INSTRUCTIONS:
# 1. Replace the placeholder values in the "CONFIGURATION" section below
#    with your actual server and database credentials.
# 2. Upload this script to your home directory on the Ubuntu server.
# 3. Make the script executable by running: chmod +x deploy.sh
# 4. Run the script from your home directory: ./deploy.sh
#
# ==============================================================================

# --- PREREQUISITES ---
# This script requires the following to be installed on the server:
# - git
# - go (version 1.18 or higher)

# --- CONFIGURATION ---
# This script uses environment variables for configuration.
# BEFORE RUNNING, you MUST set the following variables in your shell
# or in your ~/.profile, for example:
#
# export DATABASE_URL="postgres://tayap:YOUR_PASSWORD@43.228.85.200/tayap?sslmode=disable"
# export PORT="8080"
#
# DO NOT SAVE YOUR PASSWORD IN THIS SCRIPT.

# --- SCRIPT VARIABLES (Can be customized) ---
GIT_REPO_URL="<YOUR_GIT_REPOSITORY_URL>" # e.g., https://github.com/your-username/go-numerology-api.git
APP_DIR_NAME="go_numerology_api"
BINARY_NAME="numerology_api"


# --- SCRIPT LOGIC (Do not modify below this line) ---

echo "--- Starting Deployment ---"

# Check for required environment variables
if [ -z "$DATABASE_URL" ]; then
    echo "---! ERROR: DATABASE_URL environment variable is not set. Aborting. !---"
    exit 1
fi
if [ -z "$PORT" ]; then
    echo "---! WARNING: PORT environment variable is not set. Defaulting to 8080. !---"
    export PORT="8080"
fi

# 1. Navigate to the home directory
cd ~

# 2. Clone or update the repository
if [ -d "$APP_DIR_NAME" ]; then
  echo "--> Repository exists. Pulling latest changes..."
  cd "$APP_DIR_NAME"
  git pull
else
  echo "--> Cloning repository..."
  git clone "$GIT_REPO_URL" "$APP_DIR_NAME"
  cd "$APP_DIR_NAME"
fi

# Check if clone/pull was successful
if [ $? -ne 0 ]; then
    echo "---! Git clone/pull failed. Aborting deployment. !---"
    exit 1
fi


# 3. Build the Go application
echo "--> Building the Go application..."
# The source code is expected inside a subdirectory, matching our project structure.
# We build from the root of our cloned repo.
go build -o "$BINARY_NAME" ./cmd/server

# Check if build was successful
if [ ! -f "$BINARY_NAME" ]; then
    echo "---! Go build failed. Binary not found. Aborting deployment. !---"
    exit 1
fi

# 4. Stop any old running instance of the application
# NOTE: Using pkill is simple but not robust for production.
# For a production environment, it is highly recommended to create a systemd service file
# to manage the application as a proper service (e.g., handle restarts on failure).
echo "--> Stopping any existing application process..."
pkill -f "./${BINARY_NAME}" || true
sleep 2


# 5. Start the application in the background
echo "--> Starting the application on port ${PORT}..."
nohup ./"$BINARY_NAME" > app.log 2>&1 &

sleep 2

# 6. Verify that the process is running
if pgrep -f "./${BINARY_NAME}" > /dev/null; then
    echo "--- Deployment Successful! Application is running. ---"
    echo "You can view logs with: tail -f ~/go_numerology_api/app.log"
else
    echo "---! Deployment Failed! Application could not be started. !---"
    echo "Check the logs for errors: cat ~/go_numerology_api/app.log"
    exit 1
fi

exit 0
