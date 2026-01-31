package models

import (
	"time"
)

type Member struct {
	MemberID  int       `gorm:"primaryKey;column:memberid" json:"memberid"`
	AgeYear   int       `gorm:"column:ageyear" json:"ageyear"`
	Username  string    `gorm:"column:username" json:"username"`
	Password  string    `gorm:"column:password" json:"password"`
	RealName  string    `gorm:"column:realname" json:"realname"`
	Surname   string    `gorm:"column:surname" json:"surname"`
	VIPCode   string    `gorm:"column:vipcode" json:"vipcode"`
	Status    string    `gorm:"column:status" json:"status"`
	Birthday  *time.Time `gorm:"column:birthday" json:"birthday"`
	SHour     int       `gorm:"column:shour" json:"shour"`
	SMinute   int       `gorm:"column:sminute" json:"sminute"`
	SProvince string    `gorm:"column:sprovince" json:"sprovince"`
	SGender   string    `gorm:"column:sgender" json:"sgender"`
	AgeMonth  int       `gorm:"column:agemonth" json:"agemonth"`
	AgeWeek   int       `gorm:"column:ageweek" json:"ageweek"`
	AgeDay    int       `gorm:"column:ageday" json:"ageday"`
}

func (Member) TableName() string {
	return "membertb"
}

type Color struct {
	ColorID    int    `gorm:"primaryKey;column:colorid" json:"colorid"`
	DayEng     string `gorm:"column:day_eng" json:"day_eng"`
	ColorCode1 string `gorm:"column:color_code1" json:"color_code1"`
	ColorCode2 string `gorm:"column:color_code2" json:"color_code2"`
	ColorCode3 string `gorm:"column:color_code3" json:"color_code3"`
	ColorCode4 string `gorm:"column:color_code4" json:"color_code4"`
}

func (Color) TableName() string {
	return "colortb"
}

type MiracleDo struct {
	MiraID   int    `gorm:"primaryKey;column:miraid" json:"miraid"`
	Activity string `gorm:"column:activity" json:"activity"`
	DayX     string `gorm:"column:dayx" json:"dayx"`
	DayY     string `gorm:"column:dayy" json:"dayy"`
	Action   int    `gorm:"column:action" json:"action"`
	MiraRefID string `gorm:"column:mira_id" json:"mira_id"`
}

func (MiracleDo) TableName() string {
	return "miracledo"
}

type MiracleDesc struct {
	MiraID   int    `gorm:"primaryKey;column:mira_id" json:"mira_id"`
	Activity string `gorm:"column:activity" json:"activity"`
	MiraDay  string `gorm:"column:mira_day" json:"mira_day"`
	MiraDesc string `gorm:"column:mira_desc" json:"mira_desc"`
}

func (MiracleDesc) TableName() string {
	return "miracledo_desc"
}

type Article struct {
	ArtID       int       `gorm:"primaryKey;column:art_id" json:"art_id"`
	Slug        string    `gorm:"column:slug" json:"slug"`
	Title       string    `gorm:"column:title" json:"title"`
	Excerpt     string    `gorm:"column:excerpt" json:"excerpt"`
	Category    string    `gorm:"column:category" json:"category"`
	ImageURL    string    `gorm:"column:image_url" json:"image_url"`
	PublishedAt *time.Time `gorm:"column:published_at" json:"published_at"`
	IsPublished bool      `gorm:"column:is_published" json:"is_published"`
	Content     string    `gorm:"column:content" json:"content"`
	TitleShort  string    `gorm:"column:title_short" json:"title_short"`
	PinOrder    int       `gorm:"column:pin_order" json:"pin_order"`
	CreatedAt   time.Time `gorm:"column:created_at" json:"created_at"`
	UpdatedAt   time.Time `gorm:"column:updated_at" json:"updated_at"`
}

func (Article) TableName() string {
	return "articles"
}
