-- This script creates the 'numbers' table for the phone analyzer application.
-- It is designed for PostgreSQL.

CREATE TABLE IF NOT EXISTS numbers (
    pairnumberid SERIAL PRIMARY KEY,
    pairnumber VARCHAR(3) NOT NULL UNIQUE,
    pairtype VARCHAR(2) NOT NULL,
    pairpoint INT NOT NULL,
    miracledesc TEXT,
    miracledetail TEXT
);

-- Sample Data Insertion
-- This is a small subset of data for testing purposes.
-- The full table should be populated with all possible number pairs (00-99 and sums).
INSERT INTO numbers (pairnumber, pairtype, pairpoint, miracledesc, miracledetail) VALUES
('15', 'D', 20, 'เสน่ห์และปัญญา', 'เป็นคนมีเสน่ห์ มีปัญญาเฉียบแหลม ผู้คนรักใคร่'),
('24', 'D', 24, 'เมตตามหานิยม', 'เป็นที่รัก มีคนช่วยเหลือสนับสนุนเสมอ'),
('36', 'D', 20, 'ความรักและความสุข', 'ดึงดูดความรัก มีความสุขสดใส'),
('56', 'D', 24, 'ทรัพย์และปัญญา', 'มีสติปัญญาดี หาเงินเก่ง'),
('00', 'R', -20, 'ความว่างเปล่า', 'เก็บตัว ไม่เข้าสังคม โลกส่วนตัวสูง'),
('07', 'R', -18, 'ความเครียด', 'คิดมาก เครียดง่าย มีเรื่องให้กังวลเสมอ'),
('77', 'R', -10, 'เหนื่อยหนัก', 'ชีวิตต้องต่อสู้ดิ้นรน เหนื่อยทั้งกายและใจ')
ON CONFLICT (pairnumber) DO NOTHING;

COMMENT ON TABLE numbers IS 'Stores the numerological properties of number pairs.';
COMMENT ON COLUMN numbers.pairnumberid IS 'Unique identifier for the record.';
COMMENT ON COLUMN numbers.pairnumber IS 'The two-digit number pair (e.g., "15", "24") or a sum.';
COMMENT ON COLUMN numbers.pairtype IS 'The type of the pair, typically "D" for Good/Positive or "R" for Bad/Negative.';
COMMENT ON COLUMN numbers.pairpoint IS 'The score or point value associated with the number pair.';
COMMENT ON COLUMN numbers.miracledesc IS 'A short description of the pair''s meaning.';
COMMENT ON COLUMN numbers.miracledetail IS 'A more detailed explanation of the pair''s numerological significance.';
