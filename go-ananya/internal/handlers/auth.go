package handlers

import (
	"go-ananya/internal/models"
	"go-ananya/internal/repository"
	"net/http"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
)

type AuthHandler struct {
	repo *repository.UserRepository
}

func NewAuthHandler(repo *repository.UserRepository) *AuthHandler {
	return &AuthHandler{repo: repo}
}

func (h *AuthHandler) Login(c *gin.Context) {
	var input struct {
		Username string `json:"username" binding:"required"`
		Password string `json:"password" binding:"required"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	user, err := h.repo.FindByUsername(input.Username)
	if err != nil {
		c.JSON(http.StatusUnauthorized, gin.H{
			"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
			"userx":   nil,
		})
		return
	}

	// Check password using bcrypt
	err = bcrypt.CompareHashAndPassword([]byte(*user.Password), []byte(input.Password))
	if err != nil {
		// Fallback for legacy plaintext password comparison (Optional but recommended for migration)
		if *user.Password == input.Password {
			// Password matched legacy plaintext. Consider rehashing here.
		} else {
			c.JSON(http.StatusUnauthorized, gin.H{
				"serverx": gin.H{"activity": "userlogin", "message": "wrong"},
				"userx":   nil,
			})
			return
		}
	}

	c.JSON(http.StatusOK, gin.H{
		"serverx": gin.H{"activity": "userlogin", "message": "success"},
		"userx":   user,
	})
}

func (h *AuthHandler) Register(c *gin.Context) {
	var user models.Member
	if err := c.ShouldBindJSON(&user); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Check duplicate
	existing, _ := h.repo.FindByUsername(*user.Username)
	if existing != nil {
		c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "dup"})
		return
	}

	// Hash password before saving
	hashed, err := bcrypt.GenerateFromPassword([]byte(*user.Password), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to hash password"})
		return
	}
	hashedStr := string(hashed)
	user.Password = &hashedStr

	if err := h.repo.Create(&user); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"activity": "register", "message": "fail"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"activity": "register", "message": "success"})
}
