#!/bin/bash

# --- Deployment Script for Numerology Go App ---

# Stop on any error
set -e

echo "Starting deployment..."

# --- 1. Set Environment Variables ---
# IMPORTANT: You MUST set these environment variables on your server for the application to run.
# Replace the placeholder values with your actual credentials.
# For example, you can add the following lines to your ~/.bashrc or /etc/environment file on the server:
#
# export DB_HOST="your_database_host"
# export DB_USER="your_database_username"
# export DB_PASSWORD="your_database_password"
# export DB_NAME="your_database_name"
#
# This script assumes these variables are already set in the environment.

# Application settings
APP_DIR="/home/tayap/go_numerology_app" # The directory where the app will be on the server
APP_NAME="numerology_app"
GO_MAIN_PATH="./cmd" # Path to the main.go file relative to APP_DIR

# --- 2. Build the Go Application ---
echo "Building the Go application..."
cd "$APP_DIR"
go build -o "$APP_NAME" "$GO_MAIN_PATH"

echo "Build successful. Executable '$APP_NAME' created."

# --- 3. Run the Application ---
# We'll run the app in the background using 'nohup' so it keeps running after the script exits.
# The output will be logged to 'app.log'.
# First, we kill any existing process to prevent "port in use" errors.
echo "Stopping any existing application process..."
pkill -f "./$APP_NAME" || true

echo "Starting the application in the background..."
nohup "./$APP_NAME" > app.log 2>&1 &

echo "Deployment complete. The application is running."
echo "You can check the logs with: tail -f $APP_DIR/app.log"
