-- Converted from MySQL to PostgreSQL

--
-- Table structure for table "bagcolortb"
--
CREATE TABLE "bagcolortb" (
  "bag_id" SERIAL PRIMARY KEY,
  "memberid" INT NOT NULL,
  "age" VARCHAR(3),
  "bag_color1" VARCHAR(30),
  "bag_color2" VARCHAR(30),
  "bag_color3" VARCHAR(30),
  "bag_color4" VARCHAR(30),
  "bag_color5" VARCHAR(30),
  "bag_color6" VARCHAR(30),
  "bag_desc" TEXT
);

--
-- Table structure for table "colortb"
--
CREATE TABLE "colortb" (
  "colorid" SERIAL PRIMARY KEY,
  "day_eng" VARCHAR(30),
  "color_code1" VARCHAR(30),
  "color_code2" VARCHAR(30),
  "color_code3" VARCHAR(15),
  "color_code4" VARCHAR(30)
);

--
-- Table structure for table "dayspecialtb"
--
CREATE TABLE "dayspecialtb" (
  "dayid" SERIAL PRIMARY KEY,
  "wan_date" DATE NOT NULL,
  "wan_desc" TEXT,
  "wan_detail" TEXT,
  "wan_pra" BOOLEAN NOT NULL DEFAULT FALSE,
  "wan_kating" BOOLEAN NOT NULL DEFAULT FALSE,
  "wan_tongchai" BOOLEAN NOT NULL DEFAULT FALSE,
  "wan_atipbadee" BOOLEAN NOT NULL DEFAULT FALSE
);

--
-- Table structure for table "luckynumber"
--
CREATE TABLE "luckynumber" (
  "lucky_id" SERIAL PRIMARY KEY,
  "lucky_date" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "numbers" VARCHAR(90),
  "active" BOOLEAN NOT NULL DEFAULT TRUE
);


--
-- Table structure for table "membertb"
--
CREATE TABLE "membertb" (
  "memberid" SERIAL PRIMARY KEY,
  "ageyear" INT,
  "username" VARCHAR(30) UNIQUE,
  "password" VARCHAR(60),
  "realname" VARCHAR(24),
  "surname" VARCHAR(50),
  "vipcode" VARCHAR(30) NOT NULL DEFAULT 'normal',
  "status" VARCHAR(9) NOT NULL DEFAULT 'active',
  "birthday" DATE,
  "shour" INT,
  "sminute" INT,
  "sprovince" VARCHAR(30),
  "sgender" VARCHAR(1),
  "agemonth" INT,
  "ageweek" INT,
  "ageday" INT
);

--
-- Table structure for table "miracledo"
--
CREATE TABLE "miracledo" (
  "miraid" SERIAL PRIMARY KEY,
  "dayx" VARCHAR(30),
  "dayy" VARCHAR(30),
  "activity" VARCHAR(30)
);


--
-- Table structure for table "miracledo_desc"
--
CREATE TABLE "miracledo_desc" (
  "mira_id" SERIAL PRIMARY KEY,
  "mira_desc" TEXT
);

--
-- Table structure for table "news"
--
CREATE TABLE "news" (
  "newsid" SERIAL PRIMARY KEY,
  "news_topic" TEXT,
  "news_desc" TEXT,
  "news_img" VARCHAR(30),
  "news_date" TIMESTAMP WITHOUT TIME ZONE,
  "newsidtype" INT
);


--
-- Table structure for table "numbers"
--
CREATE TABLE "numbers" (
  "pairnumberid" SERIAL PRIMARY KEY,
  "pairnumber" VARCHAR(30),
  "pairtype" VARCHAR(30),
  "pairpoint" INT,
  "miracledesc" TEXT,
  "miracledetail" TEXT,
  "vip_detail" TEXT
);

--
-- Table structure for table "phonenumber_sell"
--
CREATE TABLE "phonenumber_sell" (
  "pnumber_id" SERIAL PRIMARY KEY,
  "phonenumber" VARCHAR(15),
  "price" INT
);


--
-- Table structure for table "secretcode"
--
CREATE TABLE "secretcode" (
  "codeid" SERIAL PRIMARY KEY,
  "codename" VARCHAR(30) UNIQUE,
  "codetype" VARCHAR(30),
  "codedesc" TEXT,
  "codestatus" VARCHAR(30)
);


--
-- Table structure for table "tabian_number"
--
CREATE TABLE "tabian_number" (
  "pairnumberid" SERIAL PRIMARY KEY,
  "pairnumber" VARCHAR(30),
  "pairtype" VARCHAR(30),
  "pairpoint" INT,
  "miracledesc" TEXT,
  "miracledetail" TEXT,
  "vip_detail" TEXT
);

--
-- Table structure for table "wanpra"
--
CREATE TABLE "wanpra" (
  "wanpra_id" SERIAL PRIMARY KEY,
  "wanpra_date" DATE
);
