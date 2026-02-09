# 🎉 Notification System - Ready to Deploy!

## 📦 ไฟล์ที่พร้อม Deploy

### 1. **notification_system_20260202_000439.zip** (17KB) ⭐
**นี่คือไฟล์หลักที่คุณต้อง upload!**

ประกอบด้วย:
- ✅ `app/Managers/NotificationManager.php` (NEW)
- ✅ `app/Managers/NotificationAPIController.php` (NEW)
- ✅ `app/Managers/NotificationController.php` (UPDATED)
- ✅ `app/routes.php` (UPDATED)
- ✅ `create_notifications_table.sql` (PostgreSQL)
- ✅ `README.txt` (คู่มือภาษาอังกฤษ)
- ✅ `DEPLOY_TH.txt` (คู่มือภาษาไทย)

### 2. **create_notifications_table_mysql.sql** (1.1KB)
สำหรับ MySQL database (ถ้าคุณใช้ MySQL แทน PostgreSQL)

---

## 🚀 Quick Start - Deploy ใน 4 ขั้นตอน

### Step 1: สำรองข้อมูล (BACKUP) ⚠️
```bash
# สำรอง database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# สำรองไฟล์สำคัญ
cp app/routes.php app/routes.php.backup
cp app/Managers/NotificationController.php app/Managers/NotificationController.php.backup
```

### Step 2: Upload & Extract 📤
1. Upload `notification_system_20260202_000439.zip` ไปยัง hosting
2. แตกไฟล์ที่ root directory (เช่น `/home/username/ananya-php`)
3. ไฟล์จะถูก merge เข้ากับไฟล์เดิม

### Step 3: Run Database Migration 🗄️

**ถ้าใช้ PostgreSQL:**
```bash
psql -U username -d database_name -f create_notifications_table.sql
```

**ถ้าใช้ MySQL:**
```bash
mysql -u username -p database_name < create_notifications_table_mysql.sql
```

**หรือใช้ phpMyAdmin:**
1. เข้า phpMyAdmin
2. เลือก database
3. คลิกแท็บ "SQL"
4. Copy-paste SQL จากไฟล์
5. คลิก "Go"

### Step 4: ทดสอบ ✅
```bash
# ทดสอบ API
curl "https://ananya.in.th/api/v2/notifications?memberid=TEST001&type=webview_merit"

# ควรได้ response:
# {"status":"success","data":[],"count":0}
```

---

## 📋 Deployment Checklist

- [ ] **สำรอง database แล้ว**
- [ ] **สำรองไฟล์เดิมแล้ว**
- [ ] **Upload zip file แล้ว**
- [ ] **แตกไฟล์แล้ว**
- [ ] **รัน database migration แล้ว**
- [ ] **ทดสอบ API endpoint แล้ว**
- [ ] **ส่งแจ้งเตือนทดสอบแล้ว**
- [ ] **ตรวจสอบ error logs แล้ว**

---

## 🎯 API Endpoints ใหม่

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v2/notifications` | ดึงรายการแจ้งเตือนทั้งหมด |
| GET | `/api/v2/notifications/by-type` | ดึงรายการตามประเภท |
| POST | `/api/v2/notifications/mark-read` | ทำเครื่องหมายว่าอ่านแล้ว |
| GET | `/api/v2/notifications/unread-count` | นับจำนวนที่ยังไม่อ่าน |
| GET | `/api/v2/notifications/statistics` | ดู analytics |
| POST | `/api/v2/notifications/save` | บันทึก (internal) |

---

## ✨ ฟีเจอร์ใหม่

### 1. **Database-backed Notifications**
- แจ้งเตือนทุกรายการถูกบันทึกลง database อัตโนมัติ
- ไม่หายแม้ clear app data

### 2. **Multi-device Sync**
- Login ที่อุปกรณ์ไหนก็เห็นแจ้งเตือนเดียวกัน
- ข้อมูล sync ผ่าน server

### 3. **Read Status Tracking**
- ติดตามว่าผู้ใช้อ่านแล้วหรือยัง
- บันทึกเวลาที่อ่าน

### 4. **Analytics**
- ดูสถิติการอ่านแจ้งเตือน
- แยกตามประเภท (Merit, Change, Spell, etc.)

### 5. **Backward Compatible**
- ระบบเก่า (SharedPreferences) ยังใช้งานได้
- ไม่กระทบผู้ใช้ที่ยังไม่ update app

---

## 🔍 ตรวจสอบว่า Deploy สำเร็จ

### 1. ตรวจสอบไฟล์
```bash
ls -la app/Managers/NotificationManager.php
ls -la app/Managers/NotificationAPIController.php
```

### 2. ตรวจสอบ Database
```sql
-- ตรวจสอบว่า table ถูกสร้าง
SHOW TABLES LIKE 'notifications';

-- ดูโครงสร้าง table
DESCRIBE notifications;
```

### 3. ทดสอบ API
```bash
# Test GET
curl "https://ananya.in.th/api/v2/notifications/unread-count?memberid=TEST001"

# Test POST (ส่งแจ้งเตือนทดสอบ)
# ใช้ Admin Panel: https://ananya.in.th/admin/notifications/custom
```

### 4. ตรวจสอบ Logs
```bash
tail -f error_log
tail -f fcm_log.txt
```

---

## 🐛 แก้ไขปัญหา

### ปัญหา: API ตอบกลับ 500 Error
**วิธีแก้:**
```bash
# ตรวจสอบ error log
tail -50 error_log

# ตรวจสอบ permissions
chmod 644 app/Managers/*.php
chmod 644 app/routes.php
```

### ปัญหา: Class not found
**วิธีแก้:**
```bash
# Regenerate autoloader (ถ้าใช้ Composer)
composer dump-autoload
```

### ปัญหา: Database connection failed
**วิธีแก้:**
- ตรวจสอบ database credentials ใน `configs/database.php`
- ตรวจสอบว่า database service ทำงานอยู่

---

## 📚 เอกสารเพิ่มเติม

1. **DEPLOY_GUIDE.md** - คู่มือการ deploy แบบละเอียด
2. **NOTIFICATION_API_DOCS.md** - API documentation
3. **NOTIFICATION_SYSTEM_IMPLEMENTATION.md** - สถาปัตยกรรมระบบ

---

## 🔄 Rollback (ถ้ามีปัญหา)

```bash
# 1. Restore database
mysql -u username -p database_name < backup_YYYYMMDD.sql

# 2. Restore ไฟล์
cp app/routes.php.backup app/routes.php
cp app/Managers/NotificationController.php.backup app/Managers/NotificationController.php

# 3. ลบไฟล์ใหม่
rm app/Managers/NotificationManager.php
rm app/Managers/NotificationAPIController.php

# 4. Restart web server
sudo systemctl restart apache2  # หรือ nginx
```

---

## 📞 Support

ถ้ามีปัญหา:
1. ✅ อ่าน DEPLOY_GUIDE.md
2. ✅ ตรวจสอบ error logs
3. ✅ ทดสอบ API ด้วย curl
4. ✅ ตรวจสอบ database connection

---

## 🎉 ขั้นตอนต่อไป

### Phase 1: Server-Side ✅ DONE
- ✅ Database schema
- ✅ API endpoints
- ✅ FCM integration
- ✅ Ready to deploy!

### Phase 2: Client-Side ⏳ TODO
- ⏳ Android API service
- ⏳ Fetch from server
- ⏳ Merge with local data
- ⏳ Update UI

### Phase 3: Testing & Monitoring
- ⏳ End-to-end testing
- ⏳ Performance monitoring
- ⏳ User feedback

---

## 📍 ไฟล์อยู่ที่

```
/Users/tayap/project-number/number-php/
├── notification_system_20260202_000439.zip  ← อัปโหลดไฟล์นี้!
├── create_notifications_table_mysql.sql     ← สำหรับ MySQL
├── DEPLOY_GUIDE.md                          ← อ่านคู่มือนี้
└── notification_system_deploy/              ← ไฟล์ที่แตกแล้ว
```

---

## ✅ สรุป

**คุณพร้อม deploy แล้ว!** 🚀

1. Upload `notification_system_20260202_000439.zip`
2. Extract ที่ root directory
3. Run database migration
4. ทดสอบ API endpoints
5. เสร็จสิ้น! ✨

**Good luck!** 🍀
