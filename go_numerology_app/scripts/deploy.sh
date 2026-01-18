#!/bin/bash

# This script builds the Go application for deployment.

# Exit immediately if a command exits with a non-zero status.
set -e

echo "Building Go application..."
go build -o numerology_app ../cmd/main.go

echo "Build successful!"
echo ""
echo "To run the application on your server, follow these steps:"
echo "1. Copy the 'numerology_app' executable to your server."
echo "   scp numerology_app tayap@43.228.85.200:/path/to/your/app"
echo ""
echo "2. SSH into your server:"
echo "   ssh tayap@43.228.85.200"
echo ""
echo "3. Set the DATABASE_URL environment variable (do this in your shell session):"
echo "   export DATABASE_URL=\"postgres://tayap:IntelliP24.X@localhost:5432/tayap\""
echo ""
echo "4. Run the application:"
echo "   ./numerology_app"
echo ""
echo "The application will start on port 8080."
