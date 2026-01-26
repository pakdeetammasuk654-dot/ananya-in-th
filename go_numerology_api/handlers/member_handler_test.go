package handlers

import (
	"bytes"
	"encoding/json"
	"errors"
	"go-numerology-api/models"
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/gin-gonic/gin"
	"github.com/stretchr/testify/assert"
	"golang.org/x/crypto/bcrypt"
)

// MockMemberRepository is a mock implementation of the member repository
type MockMemberRepository struct {
	CreateMemberFunc func(member *models.Member) (int, error)
}

// CreateMember calls the mock function
func (m *MockMemberRepository) CreateMember(member *models.Member) (int, error) {
	if m.CreateMemberFunc != nil {
		return m.CreateMemberFunc(member)
	}
	return 0, errors.New("CreateMemberFunc not implemented")
}



func TestRegisterMember(t *testing.T) {
	// Set Gin to test mode
	gin.SetMode(gin.TestMode)

	t.Run("Success", func(t *testing.T) {
		// Setup
		rawPassword := "password123"
		mockRepo := &MockMemberRepository{
			CreateMemberFunc: func(member *models.Member) (int, error) {
				// Assert that the password received by the repository is hashed
				// by trying to compare it with the original raw password.
				err := bcrypt.CompareHashAndPassword([]byte(member.Password), []byte(rawPassword))
				assert.NoError(t, err, "Password should be hashed")
				return 123, nil // Return a dummy ID and no error
			},
		}

		handler := &MemberHandler{Repo: mockRepo}

		router := gin.Default()
		router.POST("/member/register", handler.RegisterMember)

		// Prepare request
		// This now matches the RegisterRequest DTO
		requestBody := gin.H{
			"username": "testuser",
			"password": rawPassword,
		}
		body, _ := json.Marshal(requestBody)
		req, _ := http.NewRequest(http.MethodPost, "/member/register", bytes.NewBuffer(body))
		req.Header.Set("Content-Type", "application/json")

		// Record response
		w := httptest.NewRecorder()
		router.ServeHTTP(w, req)

		// Assertions
		assert.Equal(t, http.StatusCreated, w.Code)

		var response map[string]interface{}
		err := json.Unmarshal(w.Body.Bytes(), &response)
		assert.NoError(t, err)
		assert.Equal(t, "Member created successfully", response["message"])
		// JSON numbers are unmarshalled as float64 by default
		assert.Equal(t, float64(123), response["member_id"])
	})

	t.Run("Invalid JSON", func(t *testing.T) {
		// Setup
		mockRepo := &MockMemberRepository{}
		handler := &MemberHandler{Repo: mockRepo}
		router := gin.Default()
		router.POST("/member/register", handler.RegisterMember)

		// Prepare request with invalid body
		req, _ := http.NewRequest(http.MethodPost, "/member/register", bytes.NewBufferString(`{"username": "test",`))
		req.Header.Set("Content-Type", "application/json")

		// Record response
		w := httptest.NewRecorder()
		router.ServeHTTP(w, req)

		// Assertions
		assert.Equal(t, http.StatusBadRequest, w.Code)
	})
}
