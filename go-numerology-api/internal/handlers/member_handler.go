package handlers

import (
	"database/sql"
	"go-numerology-api/internal/database"
	"go-numerology-api/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
)

type MemberHandler struct {
	DB *sql.DB
}

func NewMemberHandler(db *sql.DB) *MemberHandler {
	return &MemberHandler{DB: db}
}

// Register handles user registration
func (h *MemberHandler) Register(c *gin.Context) {
	var req models.RegistrationRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Check if user already exists
	_, err := database.GetUserByUsername(h.DB, req.Username)
	if err == nil {
		c.JSON(http.StatusConflict, gin.H{"error": "Username already exists"})
		return
	}

	err = database.CreateUser(h.DB, &req)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create user"})
		return
	}

	c.JSON(http.StatusCreated, gin.H{"message": "User registered successfully"})
}

// Login handles user login
func (h *MemberHandler) Login(c *gin.Context) {
	var req models.LoginRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	user, err := database.GetUserByUsername(h.DB, req.Username)
	if err != nil {
		if err == sql.ErrNoRows {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Invalid username or password"})
			return
		}
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Database error"})
		return
	}

	if !database.CheckPassword(user.Password, req.Password) {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Invalid username or password"})
		return
	}

	// In a real application, generate and return a JWT token here
	c.JSON(http.StatusOK, gin.H{"message": "Login successful"})
}
