#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Configuration ---
# !!! IMPORTANT SECURITY NOTICE !!!
# The Go application requires the following environment variables to be set:
#   - PG_USER: Your PostgreSQL username
#   - PG_PASSWORD: Your PostgreSQL password
#   - PG_DBNAME: Your PostgreSQL database name
#
# You can set them temporarily for the current session like this:
#   export PG_USER="tayap"
#   export PG_PASSWORD="YOUR_VERY_SECRET_PASSWORD"
#   export PG_DBNAME="tayap"
#
# For a permanent solution, add these export lines to your ~/.bashrc or ~/.profile file.
# Do NOT hardcode credentials directly in this script for production environments.

# These values are used for the initial database/user creation by the script.
DB_USER="${PG_USER:-tayap}"
DB_PASS="${PG_PASSWORD:-<your_password_here>}" # SET YOUR PASSWORD HERE OR VIA ENV
DB_NAME="${PG_DBNAME:-tayap}"
DB_HOST="${PG_HOST:-localhost}"
DB_PORT="${PG_PORT:-5432}"

APP_DIR="/home/tayap/go-numerology-api"
SCRIPTS_DIR="${APP_DIR}/scripts"

# --- 1. System Setup ---
echo "Updating and installing dependencies..."
sudo apt-get update
sudo apt-get install -y golang-go postgresql postgresql-contrib

# --- 2. Database Setup ---
echo "Setting up PostgreSQL database..."

# Create user safely. The double-quoting and sed command help escape single quotes in the password.
sudo -u postgres psql -c "CREATE USER ${DB_USER} WITH PASSWORD '$(echo "${DB_PASS}" | sed "s/'/''/g")';" || echo "User ${DB_USER} already exists or password could not be set."

# Create the database and set the owner.
sudo -u postgres psql -c "CREATE DATABASE ${DB_NAME} OWNER ${DB_USER};" || echo "Database ${DB_NAME} already exists."

# --- 3. Application Deployment ---
echo "Deploying the application..."

# Create app directory if it doesn't exist
mkdir -p ${APP_DIR}

# NOTE: You must upload your 'go-numerology-api' project folder to the server,
# into the APP_DIR before running this script. For example:
# scp -r /path/to/your/local/go-numerology-api tayap@43.228.85.200:/home/tayap/

cd ${APP_DIR}

# --- 4. Initialize Database Schema ---
echo "Initializing database schema..."
# Run the init.sql script to create tables.
# Use PGPASSWORD to avoid password prompts.
PGPASSWORD="${DB_PASS}" psql -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USER}" -d "${DB_NAME}" -f "${SCRIPTS_DIR}/init.sql"


# --- 5. Build and Run ---
echo "Building the Go application..."

# Set environment variables for the Go application to use when running
export PG_HOST
export PG_PORT
export PG_USER
export PG_PASSWORD
export PG_DBNAME

# Tidy dependencies and build the application
go mod tidy
go build -o numerology-api ./cmd/server

echo "Stopping any existing application..."
# Stop any previously running instance of the app
pkill -f numerology-api || true

echo "Starting the application in the background..."
# Run the application in the background
nohup ./numerology-api > app.log 2>&1 &

echo "----------------------------------------"
echo "Deployment complete!"
echo "The application is running in the background."
echo "You can check the logs with: tail -f ${APP_DIR}/app.log"
echo "API should be available at http://43.228.85.200:8080"
echo "----------------------------------------"
