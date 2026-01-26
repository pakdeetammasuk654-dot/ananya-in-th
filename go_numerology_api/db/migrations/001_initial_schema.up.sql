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
    bag_desc TEXT
);

CREATE TABLE colortb (
    colorid SERIAL PRIMARY KEY,
    day_eng VARCHAR(30),
    color_code1 VARCHAR(30),
    color_code2 VARCHAR(30),
    color_code3 VARCHAR(15),
    color_code4 VARCHAR(30)
);

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

CREATE TABLE frontname (
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

CREATE TABLE luckynumber (
    lucky_id SERIAL PRIMARY KEY,
    lucky_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    numbers VARCHAR(90),
    active BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE membertb (
    memberid SERIAL PRIMARY KEY,
    ageyear INT,
    username VARCHAR(30) UNIQUE,
    password VARCHAR(13),
    realname VARCHAR(24),
    surname VARCHAR(50),
    vipcode VARCHAR(30) NOT NULL DEFAULT 'normal',
    status VARCHAR(9) NOT NULL DEFAULT 'activie',
    birthday DATE,
    shour INT,
    sminute INT,
    sprovince VARCHAR(30),
    sgender VARCHAR(1),
    agemonth INT,
    ageweek INT,
    ageday INT
);

CREATE TABLE memberuse (
    memuseid SERIAL PRIMARY KEY,
    memberid INT,
    usedate TIMESTAMP,
    usetype VARCHAR(30)
);

CREATE TABLE miracledo (
    miraid SERIAL PRIMARY KEY,
    dayx VARCHAR(30),
    activity VARCHAR(30),
    miradetail TEXT,
    photo VARCHAR(30),
    title VARCHAR(90),
    sex VARCHAR(30)
);

CREATE TABLE miracledo_desc (
    mira_id SERIAL PRIMARY KEY,
    title VARCHAR(90),
    desc_text TEXT,
    photo_url VARCHAR(255),
    dayx VARCHAR(30)
);

CREATE TABLE news (
    newsid SERIAL PRIMARY KEY,
    news_date DATE,
    news_header TEXT,
    news_detail TEXT,
    news_photo TEXT,
    news_ref TEXT,
    new_vdo TEXT,
    news_type VARCHAR(30)
);

CREATE TABLE nickname (
    nameid SERIAL PRIMARY KEY,
    day VARCHAR(9),
    thainame VARCHAR(90),
    reangthai VARCHAR(90),
    engname VARCHAR(90),
    reangeng VARCHAR(90),
    leksat_thai VARCHAR(90),
    shadow VARCHAR(90),
    leksat_eng VARCHAR(90),
    sex VARCHAR(30)
);

CREATE TABLE numbers (
    pairnumberid SERIAL PRIMARY KEY,
    pairnumber VARCHAR(9),
    pairtype VARCHAR(30),
    pairpoint INT,
    miracledesc TEXT,
    miracledetail TEXT,
    vip_detail TEXT
);

CREATE TABLE phonenumber_sell (
    pnumber_id SERIAL PRIMARY KEY,
    pnumber VARCHAR(13),
    pn_price INT,
    pn_network VARCHAR(5),
    pn_status VARCHAR(13),
    pn_desc TEXT,
    pn_photo VARCHAR(255)
);

CREATE TABLE realname (
    nameid SERIAL PRIMARY KEY,
    day VARCHAR(9),
    thainame VARCHAR(90),
    reangthai VARCHAR(90),
    engname VARCHAR(90),
    reangeng VARCHAR(90),
    leksat_thai VARCHAR(90),
    shadow VARCHAR(90),
    leksat_eng VARCHAR(90),
    sex VARCHAR(30)
);

CREATE TABLE secretcode (
    codeid SERIAL PRIMARY KEY,
    codename VARCHAR(30) UNIQUE,
    dayuse INT,
    codetype VARCHAR(30)
);

CREATE TABLE tabian_number (
    pairnumberid SERIAL PRIMARY KEY,
    pairnumber VARCHAR(9),
    pairtype VARCHAR(30),
    pairpoint INT,
    miracledesc TEXT,
    miracledetail TEXT,
    vip_detail TEXT
);

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
    topic_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    topic_date_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    topic_auth VARCHAR(90),
    public_status VARCHAR(13) NOT NULL DEFAULT 'false'
);

CREATE TABLE vipcode (
    vipid SERIAL PRIMARY KEY,
    vipcode VARCHAR(30) NOT NULL,
    userdetial VARCHAR(255) NOT NULL,
    viptype VARCHAR(30) NOT NULL,
    vipstatus VARCHAR(30) NOT NULL,
    dateactive DATE NOT NULL,
    discount INT NOT NULL
);

CREATE TABLE wanpra (
    wanpra_id SERIAL PRIMARY KEY,
    wanpra_date DATE
);
