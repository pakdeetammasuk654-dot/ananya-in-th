-- PostgreSQL initialization script

-- Drop existing tables to ensure a clean slate
DROP TABLE IF EXISTS membertb, bagcolortb, colortb, dayspecialtb, frontname, luckynumber, memberuse, miracledo, miracledo_desc, news, nickname, numbers, phonenumber_sell, realname, secretcode, tabian_number, topictb, vipcode, wanpra CASCADE;

-- Table structure for table `membertb`
CREATE TABLE membertb (
  memberid SERIAL PRIMARY KEY,
  ageyear INT,
  username VARCHAR(30) UNIQUE,
  password VARCHAR(60),
  realname VARCHAR(24),
  surname VARCHAR(50),
  vipcode VARCHAR(30) NOT NULL DEFAULT 'normal',
  status VARCHAR(9) NOT NULL DEFAULT 'active',
  birthday DATE,
  shour INT,
  sminute INT,
  sprovince VARCHAR(30),
  sgender VARCHAR(1),
  agemonth INT,
  ageweek INT,
  ageday INT
);

-- Table structure for table `bagcolortb`
CREATE TABLE bagcolortb (
  bag_id SERIAL PRIMARY KEY,
  memberid INT NOT NULL,
  age VARCHAR(3),
  bag_color1 VARCHAR(30),
  bag_color2 VARCHAR(30),
  bag_color3 VARCHAR(30),
  bag_color4 VARCHAR(30),
  bag_color5 VARCHAR(30),
  bag_color6 VARCHAR(30),
  bag_desc TEXT,
  FOREIGN KEY (memberid) REFERENCES membertb(memberid)
);

-- Table structure for table `colortb`
CREATE TABLE colortb (
  colorid SERIAL PRIMARY KEY,
  day_eng VARCHAR(30),
  color_code1 VARCHAR(30),
  color_code2 VARCHAR(30),
  color_code3 VARCHAR(15),
  color_code4 VARCHAR(30)
);

-- Table structure for table `dayspecialtb`
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

-- Table structure for table `luckynumber`
CREATE TABLE luckynumber (
  lucky_id SERIAL PRIMARY KEY,
  lucky_date TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  numbers VARCHAR(90),
  active BOOLEAN NOT NULL DEFAULT TRUE
);

-- Table structure for table news
CREATE TABLE news (
    newsid SERIAL PRIMARY KEY,
    news_date DATE,
    news_head TEXT,
    news_desc TEXT,
    news_type VARCHAR(30),
    news_photo VARCHAR(30),
    news_photo2 VARCHAR(30)
);

-- Table structure for table nickname
CREATE TABLE nickname (
    nameid SERIAL PRIMARY KEY,
    day VARCHAR(9) NOT NULL,
    thainame VARCHAR(90) NOT NULL,
    reangthai VARCHAR(90) NOT NULL,
    engname VARCHAR(90) NOT NULL,
    reangeng VARCHAR(90) NOT NULL,
    leksat_thai VARCHAR(90) NOT NULL,
    shadow VARCHAR(90) NOT NULL,
    leksat_eng VARCHAR(90) NOT NULL,
    sex VARCHAR(30) NOT NULL
);

-- Table structure for table numbers
CREATE TABLE numbers (
    pairnumberid SERIAL PRIMARY KEY,
    pairnumber VARCHAR(10),
    pairtype VARCHAR(30),
    pairpoint INT,
    miracledesc TEXT,
    miracledetail TEXT,
    vip_detail TEXT
);

-- Table structure for table realname
CREATE TABLE realname (
    nameid SERIAL PRIMARY KEY,
    day VARCHAR(9) NOT NULL,
    thainame VARCHAR(90) NOT NULL,
    reangthai VARCHAR(90) NOT NULL,
    engname VARCHAR(90) NOT NULL,
    reangeng VARCHAR(90) NOT NULL,
    leksat_thai VARCHAR(90) NOT NULL,
    shadow VARCHAR(90) NOT NULL,
    leksat_eng VARCHAR(90) NOT NULL,
    sex VARCHAR(30) NOT NULL
);


-- Table structure for table phonenumber_sell
CREATE TABLE phonenumber_sell (
    pnumber_id SERIAL PRIMARY KEY,
    pnumber VARCHAR(20),
    price INT,
    network_provider VARCHAR(30),
    pdesc TEXT,
    pdatetime TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    seller_name VARCHAR(255),
    grade VARCHAR(10)
);

-- Table structure for table secretcode
CREATE TABLE secretcode (
    codeid SERIAL PRIMARY KEY,
    codename VARCHAR(30) UNIQUE NOT NULL,
    memberid INT,
    code_desc TEXT,
    code_status VARCHAR(30),
    used_datetime TIMESTAMP WITHOUT TIME ZONE,
    created_datetime TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (memberid) REFERENCES membertb(memberid)
);

-- Table structure for table tabian_number
CREATE TABLE tabian_number (
    pairnumberid SERIAL PRIMARY KEY,
    pairnumber VARCHAR(10),
    pairtype VARCHAR(30),
    pairpoint INT,
    miracledesc TEXT,
    miracledetail TEXT,
    vip_detail TEXT
);

-- Table structure for table topictb
CREATE TABLE topictb (
    topic_id SERIAL PRIMARY KEY,
    head_text VARCHAR(255),
    desc_text VARCHAR(255),
    tag_phone VARCHAR(30) NOT NULL DEFAULT 'false',
    tag_tabian VARCHAR(30) NOT NULL DEFAULT 'false',
    tag_home VARCHAR(30) NOT NULL DEFAULT 'false',
    tag_namesur VARCHAR(30) NOT NULL DEFAULT 'false',
    paragraph1 TEXT,
    paragraph2 TEXT,
    paragraph3 TEXT,
    photo1 VARCHAR(30),
    photo2 VARCHAR(30),
    photo3 VARCHAR(30),
    topic_date TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    topic_date_update TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    topic_auth VARCHAR(90),
    public_status VARCHAR(13) NOT NULL DEFAULT 'false'
);

-- Table structure for table vipcode
CREATE TABLE vipcode (
    vipid SERIAL PRIMARY KEY,
    vipcode VARCHAR(30) NOT NULL,
    userdetial VARCHAR(255) NOT NULL,
    viptype VARCHAR(30) NOT NULL,
    vipstatus VARCHAR(30) NOT NULL,
    dateactive DATE NOT NULL,
    discount INT NOT NULL
);

-- Table structure for table wanpra
CREATE TABLE wanpra (
    wanpra_id SERIAL PRIMARY KEY,
    wanpra_date DATE
);
