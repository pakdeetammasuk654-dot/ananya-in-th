#!/bin/bash

# Configuration
SERVER_IP="43.228.85.200"
SERVER_USER="tayap"
APP_NAME="ananya-go"
REMOTE_PATH="/home/tayap/apps/ananya-go"

echo "Building for Linux amd64..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME cmd/main.go

echo "Uploading to server..."
# Using scp (will prompt for password unless SSH keys are set up)
ssh $SERVER_USER@$SERVER_IP "mkdir -p $REMOTE_PATH"
scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_PATH/
scp ananya-go.service $SERVER_USER@$SERVER_IP:$REMOTE_PATH/

echo "Setting up systemd service..."
ssh $SERVER_USER@$SERVER_IP "sudo cp $REMOTE_PATH/ananya-go.service /etc/systemd/system/ && sudo systemctl daemon-reload && sudo systemctl enable ananya-go && sudo systemctl restart ananya-go"

echo "Deployment complete!"
rm $APP_NAME
