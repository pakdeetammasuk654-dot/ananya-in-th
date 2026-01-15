#!/bin/bash

# --- Configuration ---
# IMPORTANT: Please fill in these variables with your server details.
SERVER_IP="43.228.85.200"
SSH_USER="tayap"
# Directory on the server where the application will be deployed.
REMOTE_DIR="/home/tayap/go_app"
# The name of the compiled Go application.
APP_NAME="phone_analyzer_app"

# --- Database Credentials ---
# These will be passed to the application on the server as environment variables.
# It is more secure to enter them when prompted by the script.
# Alternatively, you can hardcode them here, but it's less secure.
DB_HOST="localhost"
DB_NAME="tayap"
DB_USER="tayap"
# DB_PASS will be asked for securely below.

# --- Script Start ---
echo "Starting deployment process..."

# Securely prompt for the database password
echo -n "Please enter the database password for user '$DB_USER': "
read -s DB_PASS
echo

# Securely prompt for the SSH password if not using SSH keys
echo "You might be prompted for your SSH password."

# 1. Build the Go application for Linux
echo "Building Go application for Linux..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME ./go_project
if [ $? -ne 0 ]; then
    echo "Go build failed. Aborting deployment."
    exit 1
fi
echo "Build successful: $APP_NAME"

# 2. Deploy the application to the server
echo "Deploying application to $SSH_USER@$SERVER_IP:$REMOTE_DIR"
# Create the remote directory if it doesn't exist
ssh $SSH_USER@$SERVER_IP "mkdir -p $REMOTE_DIR"
# Copy the built application to the server
scp ./$APP_NAME $SSH_USER@$SERVER_IP:$REMOTE_DIR/
if [ $? -ne 0 ]; then
    echo "SCP failed. Could not copy application to the server. Aborting."
    rm ./$APP_NAME # Clean up local build artifact
    exit 1
fi
echo "Application copied successfully."


# 3. Run the application on the server
echo "Starting the application on the server..."
ssh $SSH_USER@$SERVER_IP << EOF
    # Kill any previously running instance of the application
    echo "Stopping any old running process..."
    pkill -f $APP_NAME || true

    # Set environment variables and run the application in the background
    echo "Starting the new process in the background..."
    export DB_HOST="$DB_HOST"
    export DB_USER="$DB_USER"
    export DB_PASS="$DB_PASS"
    export DB_NAME="$DB_NAME"

    nohup $REMOTE_DIR/$APP_NAME > $REMOTE_DIR/app.log 2>&1 &
EOF

if [ $? -ne 0 ]; then
    echo "Failed to start the application on the server."
    rm ./$APP_NAME # Clean up local build artifact
    exit 1
fi

echo "Application is running on the server. You can check the logs at $REMOTE_DIR/app.log"


# 4. Clean up local build artifacts
echo "Cleaning up local build artifacts..."
rm ./$APP_NAME

echo "Deployment finished successfully!"
