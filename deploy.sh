#!/bin/bash

# --- Configuration ---
# This script deploys the application to a remote server.
#
# Usage: ./deploy.sh <server_user> <server_ip>
# Example: ./deploy.sh myuser 192.168.1.100
#
# For better security, it is highly recommended to use SSH keys for authentication
# instead of relying on password prompts.

# Check for required arguments
if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <server_user> <server_ip>"
    exit 1
fi

SERVER_USER=$1
SERVER_IP=$2
APP_NAME="ananya-go"
REMOTE_APP_PATH="/home/${SERVER_USER}/${APP_NAME}" # Dynamic remote path

# --- Build Step ---
echo "Building Go application for Linux..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME main.go
if [ $? -ne 0 ]; then
    echo "Go build failed."
    exit 1
fi

echo "Build successful: $APP_NAME"

# --- Deployment Step ---
echo "Deploying application to $SERVER_IP..."

# Copy the binary to the server
# You will be prompted for the password here if not using SSH keys.
scp ./$APP_NAME ${SERVER_USER}@${SERVER_IP}:${REMOTE_APP_PATH}/
if [ $? -ne 0 ]; then
    echo "SCP failed. Could not copy file to server."
    exit 1
fi

echo "File copied successfully."

# --- Restart Step on Server ---
echo "Restarting the application on the server..."

# Connect via SSH and execute commands
# This command will kill any existing process with the same name and start the new one.
# It runs in the background (&) and redirects output to a log file.
ssh ${SERVER_USER}@${SERVER_IP} << EOF
  pkill -f $REMOTE_APP_PATH/$APP_NAME || true
  nohup $REMOTE_APP_PATH/$APP_NAME > $REMOTE_APP_PATH/app.log 2>&1 &
EOF

if [ $? -ne 0 ]; then
    echo "SSH command failed. Could not restart the application on the server."
    exit 1
fi

echo "Deployment complete. Application should be running on the server."

# --- Cleanup ---
echo "Cleaning up local build file..."
rm $APP_NAME

exit 0
