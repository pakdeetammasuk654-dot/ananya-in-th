#!/bin/bash

# Configuration
APP_NAME="ananya-go"
SERVER_IP="43.228.85.200"
SERVER_USER="tayap"
REMOTE_DIR="/home/tayap/apps/ananya-go"

# Security Note: Use environment variables or a secure vault for secrets in a real production environment.
# These values are currently using placeholders for security as per best practices.
# You can set these variables in your environment before running this script.
SSH_PASS=${SSH_PASS:-"FILL_YOUR_SSH_PASSWORD"}
DB_PASS=${DB_PASS:-"FILL_YOUR_DB_PASSWORD"}

echo "Building for Linux (amd64)..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME ./cmd/api

if [ $? -ne 0 ]; then
    echo "Build failed!"
    exit 1
fi

echo "Ensuring remote directory exists..."
sshpass -p "$SSH_PASS" ssh $SERVER_USER@$SERVER_IP "mkdir -p $REMOTE_DIR"

echo "Uploading binary to server..."
sshpass -p "$SSH_PASS" scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_DIR/

echo "Updating environment variables on server..."
sshpass -p "$SSH_PASS" ssh $SERVER_USER@$SERVER_IP << EOF
    cat << EOT > $REMOTE_DIR/.env
DB_HOST=localhost
DB_PORT=5432
DB_USER=tayap
DB_PASS=$DB_PASS
DB_NAME=tayap
PORT=8080
EOT

    echo "Deployment complete on server!"

    # Example of how to restart the application (if using systemd)
    # echo "Restarting service..."
    # sudo systemctl restart ananya-go

    # Or simple background process (kill old and start new)
    echo "Restarting application process..."
    pkill $APP_NAME || true
    cd $REMOTE_DIR && nohup ./$APP_NAME > app.log 2>&1 &
    echo "Application started in background."
EOF

# Clean up local binary
rm $APP_NAME
