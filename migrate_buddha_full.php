<?php
$config = include 'configs/config.php';

try {
    $db = new PDO("mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'], $config['db']['user'], $config['db']['pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Table for Buddha Pangs (Definitions)
    $sql1 = "CREATE TABLE IF NOT EXISTS buddha_pang_tb (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pang_name VARCHAR(255) NOT NULL,
        buddha_day INT DEFAULT NULL,
        description TEXT,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->exec($sql1);
    echo "Table 'buddha_pang_tb' ready.\n";

    // 2. Table for Assignments
    // We update PK to include assignment_type to allow multiple assignments per user (annual, lifetime)
    try {
        // Check if column exists, if not add it
        $db->exec("ALTER TABLE user_buddha_assign ADD COLUMN assignment_type VARCHAR(20) NOT NULL DEFAULT 'annual' AFTER memberid");
        // Drop old PK and add new composite PK
        $db->exec("ALTER TABLE user_buddha_assign DROP PRIMARY KEY, ADD PRIMARY KEY (memberid, assignment_type)");
        echo "Updated table structure for assignment types.\n";
    } catch (Exception $e) {
        // Table might be fresh or already updated
        $sql2 = "CREATE TABLE IF NOT EXISTS user_buddha_assign (
            memberid VARCHAR(50),
            assignment_type VARCHAR(20) NOT NULL DEFAULT 'annual',
            buddha_id INT,
            custom_description TEXT,
            assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (memberid, assignment_type),
            FOREIGN KEY (buddha_id) REFERENCES buddha_pang_tb(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $db->exec($sql2);
    }
    echo "Table 'user_buddha_assign' ready.\n";

    // 3. Update/Seed Buddha Pangs
    // We update existing ones or insert if missing
    // Day: 1-Sun, 2-Mon, 3-Tue, 4-WedD, 5-Thu, 6-Fri, 7-Sat, 8-WedN
    // Extra: 90-MaraVichai, 91-Prathanporn, 92-Leela, 93-OpenWorld, 94-Chinnaraj, 95-Sothorn
    $pangs = [
        ['พระประจำวันอาทิตย์ (ปางถวายเนตร)', 1, 'มุ่งเน้นการมีสติ ความเพียร และความสำเร็จในหน้าที่การงาน', '/uploads/buddha/sun.png'],
        ['พระประจำวันจันทร์ (ปางห้ามสมุทร/ห้ามญาติ)', 2, 'เน้นความร่มเย็นเป็นสุข ขจัดปัดเป่าอุปสรรค', '/uploads/buddha/mon.png'],
        ['พระประจำวันอังคาร (ปางไสยาสน์)', 3, 'เน้นความกล้าหาญ การได้รับชัยชนะ และความมีสง่าราศี', '/uploads/buddha/tue.png'],
        ['พระประจำวันพุธกลางวัน (ปางอุ้มบาตร)', 4, 'เน้นการเรียกทรัพย์สินโชคลาภ และความอุดมสมบูรณ์', '/uploads/buddha/wed_day.png'],
        ['พระประจำวันพุธกลางคืน (ปางป่าเลไลยก์)', 8, 'เน้นความสงบ ทางออกของปัญหา และการได้รับความช่วยเหลือ', '/uploads/buddha/wed_night.png'],
        ['พระประจำวันพฤหัสบดี (ปางสมาธิ)', 5, 'เน้นปัญญา ความรอบรู้ และความมั่นคงในชีวิต', '/uploads/buddha/thu.png'],
        ['พระประจำวันศุกร์ (ปางรำพึง)', 6, 'เน้นความรัก ความเมตตา และเสน่ห์มหานิยม', '/uploads/buddha/fri.png'],
        ['พระประจำวันเสาร์ (ปางนาคปรก)', 7, 'เน้นความคุ้มครอง ป้องกันภัย และความหนักแน่นมั่นคง', '/uploads/buddha/sat.png'],
        // General / Special Pangs for Lifetime
        ['พระปางมารวิชัย (ชนะมาร)', 90, 'พระพุทธรูปชนะมาร เหมาะสำหรับผู้ที่ต้องการชนะอุปสรรคศัตรูหมู่มารทั้งปวง', '/uploads/buddha/tue.png'],
        ['พระปางประทานพร', 91, 'พระพุทธรูปแห่งการให้พร เหมาะสำหรับผู้ที่ต้องการความสำเร็จสมปรารถนา', '/uploads/buddha/tue.png'],
        ['พระปางลีลา', 92, 'พระพุทธรูปแห่งความก้าวหน้า เหมาะสำหรับผู้ที่ต้องการความเจริญรุ่งเรือง', '/uploads/buddha/fri.png'],
        ['พระปางเปิดโลก', 93, 'พระพุทธรูปแห่งการเปิดทางสว่าง เหมาะสำหรับผู้ที่ต้องการทางออกและโอกาสใหม่ๆ', '/uploads/buddha/sun.png'],
        ['พระพุทธชินราช', 94, 'พระพุทธรูปศักดิ์สิทธิ์คู่บ้านคู่เมือง เสริมบารมีและอำนาจวาสนา', '/uploads/buddha/thu.png'],
        ['หลวงพ่อโสธร', 95, 'พระพุทธรูปศักดิ์สิทธิ์ที่ประทานความสำเร็จ โชคลาภ และสุขภาพแข็งแรง', '/uploads/buddha/thu.png']
    ];

    foreach ($pangs as $p) {
        // Check if day exists (Using day to identify mainly)
        $stmt = $db->prepare("SELECT id, image_url FROM buddha_pang_tb WHERE buddha_day = ?");
        $stmt->execute([$p[1]]);
        if ($row = $stmt->fetch()) {
            // Update only name and description. 
            // Update image_url ONLY if it's currently empty/null AND we have a default provided
            if (empty($row['image_url']) && !empty($p[3])) {
                $update = $db->prepare("UPDATE buddha_pang_tb SET pang_name = ?, description = ?, image_url = ? WHERE id = ?");
                $update->execute([$p[0], $p[2], $p[3], $row['id']]);
            } else {
                // Preserve existing image
                $update = $db->prepare("UPDATE buddha_pang_tb SET pang_name = ?, description = ? WHERE id = ?");
                $update->execute([$p[0], $p[2], $row['id']]);
            }
        } else {
            // Insert
            $insert = $db->prepare("INSERT INTO buddha_pang_tb (pang_name, buddha_day, description, image_url) VALUES (?, ?, ?, ?)");
            $insert->execute($p);
        }
    }
    echo "Seeded/Updated Buddha Pangs (Images preserved).\n";

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
