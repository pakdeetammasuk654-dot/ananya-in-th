#!/bin/bash
HOST="43.228.85.200"
USER="tayap"
PASS="IntelliP24.X"
REMOTE_DIR="/home/tayap/ananya-php"

echo "🚀 Starting deployment (sshpass)..."

export SSHPASS="$PASS"
sshpass -e scp -o StrictHostKeyChecking=no migrate_auspicious_missing.php "$USER@$HOST:$REMOTE_DIR/public/"

echo "✅ Upload complete! Running migration via curl..."
curl -s "https://ananya.in.th/migrate_auspicious_missing.php"
echo -e "\nExample: Visit https://ananya.in.th/migrate_auspicious_missing.php to verify."
