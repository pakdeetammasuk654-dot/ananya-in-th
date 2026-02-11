package handlers

import (
	"context"
	"numerology-api-go/models"

	"github.com/gin-gonic/gin"
	"github.com/jackc/pgx/v4"
	"golang.org/x/crypto/bcrypt"
)

type LoginPayload struct {
	Username string `json:"username"`
	Password string `json:"password"`
}

func Login(c *gin.Context) {
	var payload LoginPayload
	if err := c.ShouldBindJSON(&payload); err != nil {
		c.JSON(400, gin.H{"error": "Invalid request body"})
		return
	}

	var member models.Member
	var hashedPassword string

	query := `SELECT memberid, username, password, realname, surname, vipcode, status
			   FROM membertb
			   WHERE username = $1`

	err := DB.QueryRow(context.Background(), query, payload.Username).Scan(
		&member.MemberID, &member.Username, &hashedPassword, &member.Realname, &member.Surname,
		&member.Vipcode, &member.Status,
	)

	if err != nil {
		if err == pgx.ErrNoRows {
			c.JSON(200, gin.H{
				"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
				"userx":   nil,
			})
		} else {
			c.JSON(500, gin.H{"error": "Query error: " + err.Error()})
		}
		return
	}

	// เปรียบเทียบรหัสผ่านที่ส่งมากับ hash ในฐานข้อมูล
	err = bcrypt.CompareHashAndPassword([]byte(hashedPassword), []byte(payload.Password))
	if err != nil {
		// ถ้าไม่ตรงกัน (หรือมี error อื่นๆ) ให้ตอบว่า wrong
		c.JSON(200, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	// ไม่ส่งรหัสผ่านกลับไปใน response
	member.Password = nil

	c.JSON(200, gin.H{
		"serverx": gin.H{"activity": "userlogin", "message": "success"},
		"userx":   member,
	})
}
