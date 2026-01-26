#!/bin/bash

# Configuration
HOST="43.228.85.200"
USER="tayap"
PASS="IntelliP24.X"
REMOTE_DIR="/home/tayap/ananya-php"

echo "🚀 Starting Buddha feature deployment to $HOST..."

# Function to run scp with expect
upload_file() {
    local LOCAL_FILE=$1
    local REMOTE_PATH=$2
    
    expect <<EOF
    set timeout 120
    spawn scp "$LOCAL_FILE" "$USER@$HOST:$REMOTE_PATH"
    expect {
        "yes/no" { send "yes\r"; exp_continue }
        "password:" { send "$PASS\r" }
    }
    expect eof
EOF
}

# Function to run remote commands
run_remote() {
    local CMD=$1
    
    expect <<EOF
    set timeout 120
    spawn ssh "$USER@$HOST" "$CMD"
    expect {
        "yes/no" { send "yes\r"; exp_continue }
        "password:" { send "$PASS\r" }
    }
    expect eof
EOF
}

echo "📦 Uploading Controller..."
upload_file "app/Managers/BuddhaPangController.php" "$REMOTE_DIR/app/Managers/"

echo "📦 Uploading Views..."
upload_file "views/admin_buddha_list.php" "$REMOTE_DIR/views/"
upload_file "views/admin_buddha_form.php" "$REMOTE_DIR/views/"
upload_file "views/web_dashboard.php" "$REMOTE_DIR/views/"

echo "📦 Uploading modified Routes..."
upload_file "app/routes.php" "$REMOTE_DIR/app/"

echo "📦 Uploading Migration script..."
upload_file "migrate_buddha_full.php" "$REMOTE_DIR/"

echo "📦 Uploading Buddha Images..."
run_remote "mkdir -p $REMOTE_DIR/public/uploads/buddha"
upload_file "public/uploads/buddha/mon.png" "$REMOTE_DIR/public/uploads/buddha/"
upload_file "public/uploads/buddha/tue.png" "$REMOTE_DIR/public/uploads/buddha/"

echo "🏗️ Running database migration on remote server..."
run_remote "cd $REMOTE_DIR && php migrate_buddha_full.php"

echo "✅ Buddha feature deployment complete!"
