package models

import "time"

// Member corresponds to the membertb table
type Member struct {
	MemberID  int        `json:"member_id" db:"memberid"`
	AgeYear   *int       `json:"age_year,omitempty" db:"ageyear"`
	Username  string     `json:"username" db:"username"`
	Password  string     `json:"-" db:"password"` // Omit password from JSON responses
	Realname  *string    `json:"realname,omitempty" db:"realname"`
	Surname   *string    `json:"surname,omitempty" db:"surname"`
	Vipcode   string     `json:"vipcode" db:"vipcode"`
	Status    string     `json:"status" db:"status"`
	Birthday  *time.Time `json:"birthday,omitempty" db:"birthday"`
	SHour     *int       `json:"s_hour,omitempty" db:"shour"`
	SMinute   *int       `json:"s_minute,omitempty" db:"sminute"`
	SProvince *string    `json:"s_province,omitempty" db:"sprovince"`
	SGender   *string    `json:"s_gender,omitempty" db:"sgender"`
	AgeMonth  *int       `json:"age_month,omitempty" db:"agemonth"`
	AgeWeek   *int       `json:"age_week,omitempty" db:"ageweek"`
	AgeDay    *int       `json:"age_day,omitempty" db:"ageday"`
}
