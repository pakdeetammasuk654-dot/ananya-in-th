#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Configuration ---
# IMPORTANT: Replace these placeholders with your actual database credentials.
# It is strongly recommended to use environment variables or a secure vault for production.
DB_USER="<YOUR_DATABASE_USER>"
DB_PASSWORD="<YOUR_DATABASE_PASSWORD>"
DB_NAME="<YOUR_DATABASE_NAME>"

# The Go application will run on this port. Nginx will forward requests to it.
APP_PORT="8080"

# --- 1. System Update and Dependency Installation ---
echo "Updating package list and installing dependencies..."
sudo apt-get update
sudo apt-get install -y postgresql postgresql-contrib golang-go nginx

# --- 2. Database Setup ---
echo "Setting up PostgreSQL database and user..."
# Create a PostgreSQL user and database. The '-S' flag makes the user a superuser.
# The '|| true' part prevents the script from failing if the user/db already exists.
sudo -u postgres createuser -SDR ${DB_USER} || true
sudo -u postgres psql -c "ALTER USER ${DB_USER} WITH PASSWORD '${DB_PASSWORD}';"
sudo -u postgres createdb ${DB_NAME} -O ${DB_USER} || true

echo "Importing database schema..."
# Construct the DATABASE_URL for psql
export PGPASSWORD=$DB_PASSWORD
psql -h localhost -U ${DB_USER} -d ${DB_NAME} -f ./scripts/init.sql
unset PGPASSWORD

echo "Database setup complete."

# --- 3. Build Go Application ---
echo "Building the Go application..."
# The main.go file is in cmd/api
go build -o go-numerology-api ./cmd/api

echo "Build complete."

# --- 4. Nginx Configuration ---
echo "Configuring Nginx as a reverse proxy..."
# Create an Nginx configuration file for our application.
# This setup listens on port 80 and forwards requests to our Go app on APP_PORT.
cat <<EOF | sudo tee /etc/nginx/sites-available/go-numerology-api
server {
    listen 80;
    server_name _; # Replace with your domain name if you have one

    location / {
        proxy_pass http://localhost:${APP_PORT};
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOF

# Enable the new configuration by creating a symbolic link.
sudo ln -sf /etc/nginx/sites-available/go-numerology-api /etc/nginx/sites-enabled/
# Remove the default Nginx configuration if it exists.
sudo rm -f /etc/nginx/sites-enabled/default
# Test Nginx configuration and reload.
sudo nginx -t
sudo systemctl reload nginx

echo "Nginx configured."

# --- 5. Systemd Service Setup ---
echo "Setting up Systemd service to run the application..."
# Create a systemd service file to manage the Go application.
# This allows it to run in the background and start on boot.
cat <<EOF | sudo tee /etc/systemd/system/go-numerology-api.service
[Unit]
Description=Go Numerology API Service
After=network.target postgresql.service

[Service]
# The user/group the service will run as.
# It's good practice to create a dedicated user for this.
# For simplicity, we use the current user.
User=$(whoami)
Group=$(id -gn)

# The working directory for the application.
# Use 'pwd' which prints the current working directory.
WorkingDirectory=$(pwd)

# The command to start the application.
# We set the DATABASE_URL environment variable here.
ExecStart=$(pwd)/go-numerology-api
Environment="DATABASE_URL=postgres://${DB_USER}:${DB_PASSWORD}@localhost:5432/${DB_NAME}?sslmode=disable"

# Restart the service if it fails.
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF

# Reload systemd to recognize the new service, enable it, and start it.
sudo systemctl daemon-reload
sudo systemctl enable go-numerology-api
sudo systemctl start go-numerology-api

echo "Systemd service started."

# --- Done ---
echo "Deployment complete!"
echo "Your Go Numerology API should now be running and accessible via Nginx on port 80."
echo "You can check the service status with: sudo systemctl status go-numerology-api"
