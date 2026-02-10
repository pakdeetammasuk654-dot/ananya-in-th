package handlers

import (
	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
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
	// Original logic used LIKE for both username and password, which is insecure but we follow for now.
	// Actually, better to use exact match.
	result := config.DB.Where("username = ? AND password = ?", req.Username, req.Password).First(&user)

	if result.Error != nil {
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
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
	RealName string `json:"realname"`
	Surname  string `json:"surname"`
}

func Register(c *gin.Context) {
	var req RegisterRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Check if username already exists
	var count int64
	config.DB.Model(&models.Member{}).Where("username = ?", req.Username).Count(&count)
	if count > 0 {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "dup"})
		return
	}

	user := models.Member{
		Username: req.Username,
		Password: req.Password, // Still plain text as per original logic
		RealName: req.RealName,
		Surname:  req.Surname,
		Status:   "active",
		VipCode:  "normal",
	}

	if err := config.DB.Create(&user).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "fail"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "success"})
}
