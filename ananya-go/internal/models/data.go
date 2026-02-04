package models

import (
	"time"
)

type BagColor struct {
	BagID     int     `gorm:"primaryKey;column:bag_id"`
	MemberID  int     `gorm:"column:memberid"`
	Age       *string `gorm:"column:age"`
	BagColor1 *string `gorm:"column:bag_color1"`
	BagColor2 *string `gorm:"column:bag_color2"`
	BagColor3 *string `gorm:"column:bag_color3"`
	BagColor4 *string `gorm:"column:bag_color4"`
	BagColor5 *string `gorm:"column:bag_color5"`
	BagColor6 *string `gorm:"column:bag_color6"`
	BagDesc   *string `gorm:"column:bag_desc"`
}

func (BagColor) TableName() string {
	return "bagcolortb"
}

type Color struct {
	ColorID    int     `gorm:"primaryKey;column:colorid"`
	DayEng     *string `gorm:"column:day_eng"`
	ColorCode1 *string `gorm:"column:color_code1"`
	ColorCode2 *string `gorm:"column:color_code2"`
	ColorCode3 *string `gorm:"column:color_code3"`
	ColorCode4 *string `gorm:"column:color_code4"`
}

func (Color) TableName() string {
	return "colortb"
}

type LuckyNumber struct {
	LuckyID   int        `gorm:"primaryKey;column:lucky_id"`
	LuckyDate *time.Time `gorm:"column:lucky_date;default:CURRENT_TIMESTAMP"`
	Numbers   *string    `gorm:"column:numbers"`
	Active    int        `gorm:"column:active;default:1"`
}

func (LuckyNumber) TableName() string {
	return "luckynumber"
}

type LuckyNumberV2 struct {
	LuckyDate string `gorm:"primaryKey;column:lucky_date"`
	Num1      string `gorm:"column:num1"`
	Num2      string `gorm:"column:num2"`
	Num3      string `gorm:"column:num3"`
	Num4      string `gorm:"column:num4"`
	Num5      string `gorm:"column:num5"`
	Num6      string `gorm:"column:num6"`
}

func (LuckyNumberV2) TableName() string {
	return "luckynumber_v2"
}

type WanPra struct {
	WanPraID   int        `gorm:"primaryKey;column:wanpra_id"`
	WanPraDate *time.Time `gorm:"column:wanpra_date"`
}

func (WanPra) TableName() string {
	return "wanpra"
}

type DaySpecial struct {
	DayID         int        `gorm:"primaryKey;column:dayid"`
	WanDate       *time.Time `gorm:"column:wan_date"`
	WanDesc       *string    `gorm:"column:wan_desc"`
	WanDetail     *string    `gorm:"column:wan_detail"`
	WanPra        int        `gorm:"column:wan_pra;default:0"`
	WanKating     int        `gorm:"column:wan_kating;default:0"`
	WanTongchai   int        `gorm:"column:wan_tongchai;default:0"`
	WanAtipbadee  int        `gorm:"column:wan_atipbadee;default:0"`
}

func (DaySpecial) TableName() string {
	return "dayspecialtb"
}

type NickName struct {
	ID         int     `gorm:"primaryKey;column:id"`
	ThaiName   string  `gorm:"column:thainame"`
	ReangThai  *string `gorm:"column:reangthai"`
	LeksatThai *string `gorm:"column:leksat_thai"`
	Shadow     *string `gorm:"column:shadow"`
}

func (NickName) TableName() string {
	return "nickname"
}

type RealName struct {
	ID         int     `gorm:"primaryKey;column:id"`
	ThaiName   string  `gorm:"column:thainame"`
	ReangThai  *string `gorm:"column:reangthai"`
	LeksatThai *string `gorm:"column:leksat_thai"`
	Shadow     *string `gorm:"column:shadow"`
}

func (RealName) TableName() string {
	return "realname"
}

type NumberPair struct {
	PairID     int    `gorm:"primaryKey;column:pairid"`
	PairNumber string `gorm:"column:pairnumber"`
	PairType   string `gorm:"column:pairtype"`
	PairDesc   *string `gorm:"column:pairdesc"`
}

func (NumberPair) TableName() string {
	return "numbers"
}
