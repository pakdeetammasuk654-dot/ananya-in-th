<?php
// Direct connection using credentials from config.php
$host = "localhost";
$user = "zoqlszwh_ananyadb";
$pass = "IntelliP24.X";
$dbname = "zoqlszwh_ananyadb";

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check table existence
    $stmt = $db->query("SHOW TABLES LIKE 'phonenumber_sell'");
    if ($stmt->rowCount() == 0) {
        echo "Table 'phonenumber_sell' does NOT exist.\n";
    } else {
        echo "Table 'phonenumber_sell' exists.\n";

        // Count rows
        $stmt = $db->query("SELECT count(*) FROM phonenumber_sell");
        $count = $stmt->fetchColumn();
        echo "Total rows in 'phonenumber_sell': " . $count . "\n";

        // Check for top 4
        $stmt = $db->query("SELECT count(*) FROM phonenumber_sell WHERE phone_group = 'viptop4' AND sell_status NOT LIKE 'f'");
        $countTop4 = $stmt->fetchColumn();
        echo "Rows for Top 4 (viptop4): " . $countTop4 . "\n";

        // Fetch a sample row
        $stmt = $db->query("SELECT * FROM phonenumber_sell LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($row);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
