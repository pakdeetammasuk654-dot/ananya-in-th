package models

import "time"

// User corresponds to the membertb table in the database
type User struct {
	MemberID  int       `json:"memberid"`
	Username  string    `json:"username"`
	Password  string    `json:"-"` // "-" prevents password from being sent in JSON responses
	Realname  string    `json:"realname"`
	Surname   string    `json:"surname"`
	VipCode   string    `json:"vipcode"`
	Status    string    `json:"status"`
	Birthday  time.Time `json:"birthday"`
	// Add other fields from membertb as needed
}

// RegistrationRequest is used for binding user registration JSON data
type RegistrationRequest struct {
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
	Realname string `json:"realname" binding:"required"`
	Surname  string `json:"surname" binding:"required"`
}

// LoginRequest is used for binding user login JSON data
type LoginRequest struct {
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
}
