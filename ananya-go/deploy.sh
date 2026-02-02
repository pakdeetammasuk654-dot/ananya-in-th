#!/bin/bash

# Configuration
SERVER_IP="43.228.85.200"
SERVER_USER="tayap"
APP_NAME="ananya-go"
REMOTE_DIR="/home/tayap/ananya-go"

echo "Building for Linux..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME cmd/server/main.go

if [ $? -ne 0 ]; then
    echo "Build failed!"
    exit 1
fi

echo "Deploying to $SERVER_IP..."

# Create directory on server
ssh $SERVER_USER@$SERVER_IP "mkdir -p $REMOTE_DIR"

# Copy binary and service file
scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_DIR/
scp ananya-go.service $SERVER_USER@$SERVER_IP:$REMOTE_DIR/

# Copy .env.example if .env doesn't exist on server
ssh $SERVER_USER@$SERVER_IP "[ ! -f $REMOTE_DIR/.env ] && cp $REMOTE_DIR/.env.example $REMOTE_DIR/.env"

echo "Deployment complete. To start the service, run:"
echo "sudo cp $REMOTE_DIR/ananya-go.service /etc/systemd/system/"
echo "sudo systemctl daemon-reload"
echo "sudo systemctl enable $APP_NAME"
echo "sudo systemctl restart $APP_NAME"
