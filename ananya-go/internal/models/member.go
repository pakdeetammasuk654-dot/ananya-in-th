package models

import "time"

type Member struct {
	MemberID  int       `gorm:"primaryKey;column:memberid;autoIncrement" json:"member_id"`
	AgeYear   int       `gorm:"column:ageyear" json:"age_year"`
	Username  string    `gorm:"column:username;unique" json:"username"`
	Password  string    `gorm:"column:password" json:"password"`
	RealName  string    `gorm:"column:realname" json:"real_name"`
	Surname   string    `gorm:"column:surname" json:"surname"`
	VipCode   string    `gorm:"column:vipcode;default:normal" json:"vip_code"`
	Status    string    `gorm:"column:status;default:active" json:"status"`
	Birthday  *time.Time `gorm:"column:birthday" json:"birthday"`
	SHour     int       `gorm:"column:shour" json:"s_hour"`
	SMinute   int       `gorm:"column:sminute" json:"s_minute"`
	SProvince string    `gorm:"column:sprovince" json:"s_province"`
	SGender   string    `gorm:"column:sgender" json:"s_gender"`
	AgeMonth  int       `gorm:"column:agemonth" json:"age_month"`
	AgeWeek   int       `gorm:"column:ageweek" json:"age_week"`
	AgeDay    int       `gorm:"column:ageday" json:"age_day"`
}

func (Member) TableName() string {
	return "membertb"
}
