package handlers

import (
	"go-numerology-api/models"
	"go-numerology-api/repository"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
)

// MemberHandler handles member-related requests
type MemberHandler struct {
	Repo repository.MemberRepositoryInterface
}

// NewMemberHandler creates a new MemberHandler
func NewMemberHandler(repo repository.MemberRepositoryInterface) *MemberHandler {
	return &MemberHandler{Repo: repo}
}

// RegisterRequest defines the shape of the registration request body
type RegisterRequest struct {
	Username string     `json:"username" binding:"required"`
	Password string     `json:"password" binding:"required"`
	Realname *string    `json:"realname"`
	Surname  *string    `json:"surname"`
	Birthday *time.Time `json:"birthday"`
}

// RegisterMember handles the registration of a new member
func (h *MemberHandler) RegisterMember(c *gin.Context) {
	var req RegisterRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	// Hash the password
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(req.Password), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to hash password"})
		return
	}

	// Map request DTO to the domain model
	member := &models.Member{
		Username: req.Username,
		Password: string(hashedPassword),
		Realname: req.Realname,
		Surname:  req.Surname,
		Birthday: req.Birthday,
	}

	memberID, err := h.Repo.CreateMember(member)
	if err != nil {
		// In a real app, you should check for specific errors,
		// like a duplicate username, and return a proper status code.
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create member"})
		return
	}

	c.JSON(http.StatusCreated, gin.H{"message": "Member created successfully", "member_id": memberID})
}
