package handlers

import (
	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
)

type LoginRequest struct {
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
}

func Login(c *gin.Context) {
	var req LoginRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var user models.Member
	if err := config.DB.Where("username = ?", req.Username).First(&user).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	// In the original PHP, passwords might be plain text.
	// For migration, we check if it matches plain text first (for legacy)
	// or matches bcrypt hash.
	err := bcrypt.CompareHashAndPassword([]byte(user.Password), []byte(req.Password))
	if err != nil && user.Password != req.Password {
		c.JSON(http.StatusOK, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"serverx": gin.H{"activity": "userlogin", "message": "success"},
		"userx":   user,
	})
}

type RegisterRequest struct {
	RealName string `json:"realname"`
	Surname  string `json:"surname"`
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
}

func Register(c *gin.Context) {
	var req RegisterRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var existingUser models.Member
	if err := config.DB.Where("username = ?", req.Username).First(&existingUser).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "dup"})
		return
	}

	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(req.Password), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"activity": "register", "message": "error_hashing"})
		return
	}

	newUser := models.Member{
		RealName: req.RealName,
		Surname:  req.Surname,
		Username: req.Username,
		Password: string(hashedPassword),
		Status:   "active",
		VipCode:  "normal",
	}

	if err := config.DB.Create(&newUser).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "fail"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "success"})
}
