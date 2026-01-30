#!/bin/bash

# Configuration - Set these or use Environment Variables
SERVER_IP="${SERVER_IP:-43.228.85.200}"
SERVER_USER="${SERVER_USER:-tayap}"
APP_NAME="go-ananya"
REMOTE_DIR="/home/tayap/app"

echo "Building for Linux..."
GOOS=linux GOARCH=amd64 go build -o $APP_NAME cmd/server/main.go

echo "Deploying to $SERVER_IP..."
# Note: It's recommended to use SSH Keys for security.
scp $APP_NAME $SERVER_USER@$SERVER_IP:$REMOTE_DIR/

echo "Restarting service on server..."
# Note: On production, using a systemd service is recommended over nohup.
ssh $SERVER_USER@$SERVER_IP << EOF
  cd $REMOTE_DIR
  pkill $APP_NAME || true

  # Ensure these are set on the server or passed during execution
  export DB_HOST="localhost"
  export DB_USER="\$DB_USER"
  export DB_PASSWORD="\$DB_PASSWORD"
  export DB_NAME="\$DB_NAME"
  export DB_PORT="5432"
  export PORT="8080"

  nohup ./$APP_NAME > app.log 2>&1 &
  echo "Application started."
EOF

echo "Deployment complete!"
