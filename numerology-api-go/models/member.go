package models

import "time"

type Member struct {
	MemberID  int       `json:"memberid"`
	AgeYear   *int      `json:"ageyear"`
	Username  *string   `json:"username"`
	Password  *string   `json:"password"`
	Realname  *string   `json:"realname"`
	Surname   *string   `json:"surname"`
	Vipcode   string    `json:"vipcode"`
	Status    string    `json:"status"`
	Birthday  *time.Time `json:"birthday"`
	SHour     *int      `json:"shour"`
	SMinute   *int      `json:"sminute"`
	SProvince *string   `json:"sprovince"`
	SGender   *string   `json:"sgender"`
	AgeMonth  *int      `json:"agemonth"`
	AgeWeek   *int      `json:"ageweek"`
	AgeDay    *int      `json:"ageday"`
}
