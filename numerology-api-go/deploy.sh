#!/bin/bash

# --- การตั้งค่าที่ต้องแก้ไข ---
export DB_USER="<YOUR_DB_USERNAME>"
export DB_PASSWORD="<YOUR_DB_PASSWORD>"
export DB_HOST="<YOUR_DB_HOST>" # เช่น localhost หรือ IP ของ DB Server
export DB_NAME="<YOUR_DB_NAME>"

# --- ตัวแปรสำหรับสคริปต์ ---
APP_NAME="numerology-api-go"
LOG_FILE="api.log"
APP_DIR="/path/to/your/project/numerology-api-go" # **สำคัญ:** แก้ไขเป็น path จริงบนเซิร์ฟเวอร์ของคุณ

# --- เริ่มการ Deploy ---
echo "Starting deployment for $APP_NAME..."

# 1. เข้าไปยัง Directory ของโปรเจกต์
cd $APP_DIR || { echo "Project directory not found!"; exit 1; }

# 2. (Optional) ดึงโค้ดล่าสุดจาก Git
# echo "Pulling latest code from git..."
# git pull origin main

# 3. หยุดการทำงานของเซิร์ฟเวอร์เก่า (ถ้ามี)
echo "Stopping old server process..."
pkill -f "./$APP_NAME" || true
sleep 2

# 4. Build โปรเจกต์ Go
echo "Building the Go application..."
go build -o $APP_NAME . || { echo "Go build failed!"; exit 1; }

# 5. รันเซิร์ฟเวอร์ใหม่เป็น Background Process
echo "Starting new server process..."
nohup ./$APP_NAME > $LOG_FILE 2>&1 &

sleep 2

# 6. ตรวจสอบสถานะ
if pgrep -f "./$APP_NAME"; then
    echo "Deployment successful! Application is running."
    echo "View logs with: tail -f $LOG_FILE"
else
    echo "Deployment failed! Application did not start."
    echo "Check logs for errors: cat $LOG_FILE"
fi
