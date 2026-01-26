#!/bin/bash

# Stop on any error
set -e

# --- Configuration ---
# IMPORTANT: You must provide your database credentials as environment variables
# before running this script.
#
# Option 1: Create a file named '.env' in the project root with the following content:
#
# PGHOST=your_server_ip
# PGPORT=5432
# PGDATABASE=tayap
# PGUSER=tayap
# PGPASSWORD=YourSecurePassword
#
# Option 2: Export them in your terminal before running the script:
#
# export PGHOST=43.228.85.200
# ... and so on for the other variables.

echo "--- Starting Deployment ---"

# Navigate to the script's directory to ensure relative paths are correct
cd "$(dirname "$0")/.."

# Load environment variables from .env file if it exists
if [ -f .env ]; then
    echo "Loading environment variables from .env file..."
    export $(cat .env | xargs)
fi

# Check for required environment variables
if [ -z "$PGHOST" ] || [ -z "$PGUSER" ] || [ -z "$PGPASSWORD" ] || [ -z "$PGDATABASE" ]; then
    echo "ERROR: Database environment variables (PGHOST, PGUSER, PGPASSWORD, PGDATABASE) are not set."
    echo "Please create a .env file or export them in your shell."
    exit 1
fi

echo "1. Building the Go application..."
go build -o numerology_api cmd/api/main.go
echo "Build complete. Executable 'numerology_api' created."

echo "2. Setting up the database schema..."
# The \i command in psql executes commands from a file.
# The -v ON_ERROR_STOP=1 ensures that the script will exit if an SQL error occurs.
psql -v ON_ERROR_STOP=1 < scripts/schema_pg.sql
echo "Database schema setup complete."

echo "3. Starting the application..."
# Kill any existing process running on port 8080 to prevent errors
if lsof -t -i:8080; then
    echo "Found existing process on port 8080. Killing it..."
    kill $(lsof -t -i:8080)
fi

# Start the application in the background using nohup
# This allows the process to keep running even if the terminal session is closed.
# Output is redirected to app.log.
nohup ./numerology_api > app.log 2>&1 &

echo "--- Deployment Finished ---"
echo "The application is now running in the background on port 8080."
echo "You can view logs with the command: tail -f app.log"
