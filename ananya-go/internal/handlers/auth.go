package handlers

import (
	"net/http"

	"ananya-go/internal/config"
	"ananya-go/internal/models"

	"github.com/gin-gonic/gin"
)

func Login(c *gin.Context) {
	var input struct {
		Username string `json:"username" binding:"required"`
		Password string `json:"password" binding:"required"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var member models.Member
	if err := config.DB.Where("username = ? AND password = ?", input.Username, input.Password).First(&member).Error; err != nil {
		c.JSON(http.StatusUnauthorized, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"serverx": gin.H{"activity": "userlogin", "message": "success"},
		"userx":   member,
	})
}

func Register(c *gin.Context) {
	var input models.Member
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Check if username exists
	var existing models.Member
	if err := config.DB.Where("username = ?", input.Username).First(&existing).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "dup"})
		return
	}

	if err := config.DB.Create(&input).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"activity": "register", "message": "fail"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "success"})
}
