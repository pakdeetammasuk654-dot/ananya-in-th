package handlers

import (
	"ananya-go/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

type AuthHandler struct {
	DB *gorm.DB
}

func NewAuthHandler(db *gorm.DB) *AuthHandler {
	return &AuthHandler{DB: db}
}

func (h *AuthHandler) Login(c *gin.Context) {
	var body struct {
		Username string `form:"username" json:"username" binding:"required"`
		Password string `form:"password" json:"password" binding:"required"`
	}

	if err := c.ShouldBind(&body); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var user models.Member
	if err := h.DB.Where("username = ?", body.Username).First(&user).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	// Verify password
	if err := bcrypt.CompareHashAndPassword([]byte(*user.Password), []byte(body.Password)); err != nil {
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

func (h *AuthHandler) Register(c *gin.Context) {
	var body struct {
		Realname string `form:"realname" json:"realname"`
		Surname  string `form:"surname" json:"surname"`
		Username string `form:"username" json:"username" binding:"required"`
		Password string `form:"password" json:"password" binding:"required"`
	}

	if err := c.ShouldBind(&body); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Check duplicate
	var count int64
	h.DB.Model(&models.Member{}).Where("username = ?", body.Username).Count(&count)
	if count > 0 {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "dup"})
		return
	}

	// Hash password
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(body.Password), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "failed to hash password"})
		return
	}
	hashedPasswordStr := string(hashedPassword)

	user := models.Member{
		RealName: &body.Realname,
		Surname:  &body.Surname,
		Username: &body.Username,
		Password: &hashedPasswordStr,
		VipCode:  "normal",
		Status:   "active",
	}

	if err := h.DB.Create(&user).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "fail"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "success"})
}
