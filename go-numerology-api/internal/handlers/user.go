package handlers

import (
	"database/sql"
	"go-numerology-api/internal/models"
	"net/http"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
)

// UserHandler holds the database connection.
type UserHandler struct {
	DB *sql.DB
}

// RegisterUser handles new user registration.
func (h *UserHandler) RegisterUser(c *gin.Context) {
	var user models.User
	if err := c.ShouldBindJSON(&user); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid request body"})
		return
	}

	// Basic validation
	if user.Username == "" || user.Password == "" {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Username and password are required"})
		return
	}

	// Hash the password
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to hash password"})
		return
	}

	// Insert user into the database
	query := `
		INSERT INTO membertb (username, password, realname, surname)
		VALUES ($1, $2, $3, $4)
		RETURNING memberid, vipcode, status
	`
	err = h.DB.QueryRow(
		query,
		user.Username,
		string(hashedPassword),
		user.RealName,
		user.Surname,
	).Scan(&user.MemberID, &user.VIPCode, &user.Status)

	if err != nil {
		// This could be a unique constraint violation (username already exists)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to register user"})
		return
	}

	// Don't return the password hash
	user.Password = ""

	c.JSON(http.StatusCreated, user)
}
