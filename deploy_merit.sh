#!/bin/bash
HOST="43.228.85.200"
USER="tayap"
PASS="IntelliP24.X"
REMOTE_BASE="/home/tayap/ananya-php"

echo "🚀 Retry Deploying Merit View and Routes..."

# Upload Routes
echo "📦 Uploading routes.php..."
sshpass -p "$PASS" scp -o StrictHostKeyChecking=no app/routes.php "$USER@$HOST:$REMOTE_BASE/app/routes.php"

# Upload View
echo "📦 Uploading web_merit_view.php..."
sshpass -p "$PASS" scp -o StrictHostKeyChecking=no views/web_merit_view.php "$USER@$HOST:$REMOTE_BASE/views/web_merit_view.php"

echo "✅ Maintainer: Fixed script syntax"
