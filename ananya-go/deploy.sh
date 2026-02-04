#!/bin/bash

# Configuration
SERVER_IP="43.228.85.200"
SERVER_USER="tayap"
APP_NAME="ananya-go"
REMOTE_PATH="/home/tayap/ananya-go"

echo "--- Building for Linux amd64 ---"
GOOS=linux GOARCH=amd64 go build -o $APP_NAME ./cmd/api

if [ $? -ne 0 ]; then
    echo "Build failed!"
    exit 1
fi

echo "--- Uploading to Server ---"
# We'll use sshpass if available, or just standard scp (user will be prompted if no keys)
scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_PATH

echo "--- Restarting Service ---"
ssh $SERVER_USER@$SERVER_IP "sudo systemctl restart $APP_NAME"

echo "--- Done ---"
