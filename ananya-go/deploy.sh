#!/bin/bash

# Configuration
APP_NAME="ananya-go"
SERVER_IP="43.228.85.200"
SERVER_USER="tayap"
REMOTE_DIR="/home/tayap/ananya-go"

echo "Building for Linux..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME cmd/server/main.go

echo "Creating remote directory..."
ssh $SERVER_USER@$SERVER_IP "mkdir -p $REMOTE_DIR"

echo "Uploading binary and service file..."
scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_DIR/
scp ananya-go.service $SERVER_USER@$SERVER_IP:$REMOTE_DIR/

echo "Setting up systemd service..."
ssh $SERVER_USER@$SERVER_IP "sudo cp $REMOTE_DIR/ananya-go.service /etc/systemd/system/ && sudo systemctl daemon-reload && sudo systemctl enable ananya-go && sudo systemctl restart ananya-go"

echo "Deployment complete."
