package models

import (
	"time"
)

type Member struct {
	MemberID  uint32    `gorm:"primaryKey;column:memberid;autoIncrement" json:"member_id"`
	AgeYear   *int32    `gorm:"column:ageyear" json:"age_year"`
	Username  string    `gorm:"column:username;unique;size:30" json:"username"`
	Password  string    `gorm:"column:password;size:255" json:"-"` // Increased size for future hashing
	RealName  string    `gorm:"column:realname;size:24" json:"real_name"`
	Surname   string    `gorm:"column:surname;size:50" json:"surname"`
	VipCode   string    `gorm:"column:vipcode;default:normal;size:30" json:"vip_code"`
	Status    string    `gorm:"column:status;default:active;size:9" json:"status"`
	Birthday  *time.Time `gorm:"column:birthday;type:date" json:"birthday"`
	SHour     *int32    `gorm:"column:shour" json:"s_hour"`
	SMinute   *int32    `gorm:"column:sminute" json:"s_minute"`
	SProvince string    `gorm:"column:sprovince;size:30" json:"s_province"`
	SGender   string    `gorm:"column:sgender;size:1" json:"s_gender"`
	AgeMonth  *int32    `gorm:"column:agemonth" json:"age_month"`
	AgeWeek   *int32    `gorm:"column:ageweek" json:"age_week"`
	AgeDay    *int32    `gorm:"column:ageday" json:"age_day"`
	FcmToken  string    `gorm:"column:fcm_token;size:255" json:"fcm_token"`
}

func (Member) TableName() string {
	return "membertb"
}
