-- PostgreSQL Schema for Numerology API

-- Drop tables if they exist to ensure a clean slate
DROP TABLE IF EXISTS membertb, bagcolortb, colortb, dayspecialtb, luckynumber, miracledo, miracledo_desc, news, numbers, secretcode, tabian_number, topictb, vipcode, wanpra CASCADE;

-- Table for members/users
CREATE TABLE membertb (
  memberid SERIAL PRIMARY KEY,
  ageyear INTEGER,
  username VARCHAR(30) UNIQUE,
  password VARCHAR(255), -- Increased length for bcrypt hashes
  realname VARCHAR(50),
  surname VARCHAR(50),
  vipcode VARCHAR(30) NOT NULL DEFAULT 'normal',
  status VARCHAR(9) NOT NULL DEFAULT 'active',
  birthday DATE,
  shour INTEGER,
  sminute INTEGER,
  sprovince VARCHAR(30),
  sgender VARCHAR(1),
  agemonth INTEGER,
  ageweek INTEGER,
  ageday INTEGER,
  fcm_token TEXT
);

-- Table for bag colors based on age
CREATE TABLE bagcolortb (
  bag_id SERIAL PRIMARY KEY,
  memberid INTEGER REFERENCES membertb(memberid),
  age VARCHAR(3),
  bag_color1 VARCHAR(30),
  bag_color2 VARCHAR(30),
  bag_color3 VARCHAR(30),
  bag_color4 VARCHAR(30),
  bag_color5 VARCHAR(30),
  bag_color6 VARCHAR(30),
  bag_desc TEXT
);

-- Table for auspicious colors per day
CREATE TABLE colortb (
  colorid SERIAL PRIMARY KEY,
  day_eng VARCHAR(30),
  color_code1 VARCHAR(30),
  color_code2 VARCHAR(30),
  color_code3 VARCHAR(15),
  color_code4 VARCHAR(30)
);

-- Table for special days (Buddhist holy days, etc.)
CREATE TABLE dayspecialtb (
  dayid SERIAL PRIMARY KEY,
  wan_date DATE NOT NULL,
  wan_desc TEXT,
  wan_detail TEXT,
  wan_pra BOOLEAN NOT NULL DEFAULT FALSE,
  wan_kating BOOLEAN NOT NULL DEFAULT FALSE,
  wan_tongchai BOOLEAN NOT NULL DEFAULT FALSE,
  wan_atipbadee BOOLEAN NOT NULL DEFAULT FALSE
);

-- Table for lucky numbers
CREATE TABLE luckynumber (
  lucky_id SERIAL PRIMARY KEY,
  lucky_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  numbers VARCHAR(90),
  active BOOLEAN NOT NULL DEFAULT TRUE
);

-- Table for "Miracle Do" activities
CREATE TABLE miracledo (
  miraid SERIAL PRIMARY KEY,
  activity VARCHAR(30),
  dayx VARCHAR(30),
  dayy VARCHAR(30),
  mira_id INTEGER
);

-- Descriptions for "Miracle Do"
CREATE TABLE miracledo_desc (
  mira_id INTEGER PRIMARY KEY,
  mira_desc TEXT,
  mira_detail TEXT
);

-- News/Articles table
CREATE TABLE news (
  newsid SERIAL PRIMARY KEY,
  newstype VARCHAR(30),
  newstopic VARCHAR(255),
  newsdetail TEXT,
  newsphoto VARCHAR(30)
);

-- General number meanings (for phone numbers)
CREATE TABLE numbers (
  pairnumberid SERIAL PRIMARY KEY,
  pairnumber VARCHAR(4),
  pairtype VARCHAR(30),
  pairpoint INTEGER,
  miracledesc TEXT,
  miracledetail TEXT,
  vip_detail TEXT
);

-- VIP Codes
CREATE TABLE secretcode (
  codeid SERIAL PRIMARY KEY,
  codename VARCHAR(90) UNIQUE,
  codetype VARCHAR(30),
  codestatus VARCHAR(30),
  dateadd DATE
);

-- Meanings for license plate numbers
CREATE TABLE tabian_number (
  pairnumberid SERIAL PRIMARY KEY,
  pairnumber VARCHAR(4),
  pairtype VARCHAR(30),
  pairpoint INTEGER,
  miracledesc TEXT,
  miracledetail TEXT,
  vip_detail TEXT
);

-- Topics (seems related to articles)
CREATE TABLE topictb (
  topic_id SERIAL PRIMARY KEY,
  head_text VARCHAR(255),
  desc_text VARCHAR(255),
  tag_phone VARCHAR(30) DEFAULT 'false',
  tag_tabian VARCHAR(30) DEFAULT 'false',
  tag_home VARCHAR(30) DEFAULT 'false',
  tag_namesur VARCHAR(30) DEFAULT 'false',
  paragraph1 TEXT,
  paragraph2 TEXT,
  paragraph3 TEXT,
  photo1 VARCHAR(30),
  photo2 VARCHAR(30),
  photo3 VARCHAR(30),
  topic_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  topic_date_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  topic_auth VARCHAR(90),
  public_status VARCHAR(13) DEFAULT 'false'
);

-- VIP Code types/definitions
CREATE TABLE vipcode (
  vipid SERIAL PRIMARY KEY,
  vipcode VARCHAR(30) NOT NULL,
  userdetial VARCHAR(255) NOT NULL,
  viptype VARCHAR(30) NOT NULL,
  vipstatus VARCHAR(30) NOT NULL,
  dateactive DATE NOT NULL,
  discount INTEGER NOT NULL
);

-- Buddhist holy days
CREATE TABLE wanpra (
  wanpra_id SERIAL PRIMARY KEY,
  wanpra_date DATE
);

-- Insert initial data for colortb
INSERT INTO colortb (day_eng, color_code1, color_code2, color_code3, color_code4) VALUES
('Sunday', '#FF0000', '#CC0000', '#FF3333', '#990000'),
('Monday', '#FFFFFF', '#FFFFCC', '#FFFF99', '#FFFFC2'),
('Tuesday', '#FF00FF', '#FF66FF', '#CC00CC', '#FF007F'),
('Wednesday', '#009900', '#006600', '#00CC00', '#336600'),
('Thursday', '#FFFF00', '#FF8000', '#FFFF33', '#CC6600'),
('Friday', '#00FFFF', '#0080FF', '#0000FF', '#66B2FF'),
('Saturday', '#000000', '#6600CC', '#9933FF', '#4C0099'),
('Wednesday2', '#663300', '#808080', '#C0C0C0', '#E0E0E0');

-- Insert initial data for vipcode
INSERT INTO vipcode (vipcode, userdetial, viptype, vipstatus, dateactive, discount) VALUES
('ymnap159565bn', 'อณัญญา', 'admin', 'active', '2020-01-01', 0),
('pmnt9514542pm', 'ภูมิธนันทน์', 'admin', 'active', '2020-01-01', 0),
('VIPNICKN', 'ดู list ชื่อเล่นได้', 'VIPNICKN', 'active', '2020-01-01', 0),
('VIPRNSN', 'ดู list ชื่อจริง', 'VIPRNSN', 'active', '2020-01-01', 0),
('VIPTBR', 'ดูทะเบียนรถได้ไม่อั้น', 'VIPTBR', 'active', '2020-01-01', 5),
('VIPPLN', 'ดูตารางดวงได้ ทำนายทะเบียนรถได้', 'VIPPLN', 'active', '2020-01-01', 10),
('VIPGOLD', 'ดู List ชื่อ เล่นชื่อจริงได้', 'VIPGOLD', 'active', '2020-01-01', 10),
('VIPDIMOND', 'ทำได้หมด', 'VIPDIMOND', 'active', '2020-01-01', 13);

-- Add some indexes for performance
CREATE INDEX idx_membertb_username ON membertb(username);
CREATE INDEX idx_bagcolortb_memberid ON bagcolortb(memberid);
CREATE INDEX idx_miracledo_activity ON miracledo(activity);
CREATE INDEX idx_miracledo_dayx ON miracledo(dayx);
CREATE INDEX idx_miracledo_dayy ON miracledo(dayy);
