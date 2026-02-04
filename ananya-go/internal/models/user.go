package models

import (
	"time"
)

type Member struct {
	MemberID  int        `gorm:"primaryKey;column:memberid"`
	AgeYear   *int       `gorm:"column:ageyear"`
	Username  *string    `gorm:"column:username"`
	Password  *string    `gorm:"column:password"`
	RealName  *string    `gorm:"column:realname"`
	Surname   *string    `gorm:"column:surname"`
	VipCode   string     `gorm:"column:vipcode;default:normal"`
	Status    string     `gorm:"column:status;default:active"`
	Birthday  *time.Time `gorm:"column:birthday"`
	SHour     *int       `gorm:"column:shour"`
	SMinute   *int       `gorm:"column:sminute"`
	SProvince *string    `gorm:"column:sprovince"`
	SGender   *string    `gorm:"column:sgender"`
	AgeMonth  *int       `gorm:"column:agemonth"`
	AgeWeek   *int       `gorm:"column:ageweek"`
	AgeDay    *int       `gorm:"column:ageday"`
	FcmToken  *string    `gorm:"column:fcm_token"`
}

func (Member) TableName() string {
	return "membertb"
}

type MemberUse struct {
	MemUseID int     `gorm:"primaryKey;column:memuseid"`
	VipType  string  `gorm:"column:viptype"`
	CodeName string  `gorm:"column:codename"`
	MemberID int     `gorm:"column:memberid"`
	DateAdd  *string `gorm:"column:dateadd"`
}

func (MemberUse) TableName() string {
	return "memberuse"
}

type VipCode struct {
	VipID      int        `gorm:"primaryKey;column:vipid"`
	VipCode    string     `gorm:"column:vipcode"`
	UserDetail string     `gorm:"column:userdetial"`
	VipType    string     `gorm:"column:viptype"`
	VipStatus  string     `gorm:"column:vipstatus"`
	DateActive *time.Time `gorm:"column:dateactive"`
	Discount   int        `gorm:"column:discount"`
}

func (VipCode) TableName() string {
	return "vipcode"
}

type SecretCode struct {
	CodeID     int     `gorm:"primaryKey;column:codeid"`
	CodeType   *string `gorm:"column:codetype"`
	CodeName   *string `gorm:"column:codename"`
	CodeStatus string  `gorm:"column:codestatus;default:active"`
}

func (SecretCode) TableName() string {
	return "secretcode"
}
