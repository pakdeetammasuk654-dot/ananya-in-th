package handlers

import (
	"go-numerology-api/models"
	"go-numerology-api/repository"
	"net/http"
	"regexp"

	"github.com/gin-gonic/gin"
)

// PhoneHandler handles phone number analysis requests
type PhoneHandler struct {
	Repo *repository.NumberRepository
}

// NewPhoneHandler creates a new PhoneHandler
func NewPhoneHandler(repo *repository.NumberRepository) *PhoneHandler {
	return &PhoneHandler{Repo: repo}
}

// AnalyzePhoneNumber handles the analysis of a given phone number
func (h *PhoneHandler) AnalyzePhoneNumber(c *gin.Context) {
	phoneNumber := c.Param("phoneNumber")

	// Basic validation: ensure it contains only digits and is a reasonable length
	re := regexp.MustCompile(`^[0-9]{9,10}$`)
	if !re.MatchString(phoneNumber) {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid phone number format. It should be 9-10 digits."})
		return
	}

	analysis := models.PhoneAnalysis{
		PhoneNumber: phoneNumber,
		Pairs:       []models.PairMeaning{},
		TotalScore:  0,
	}

	// Analyze pairs
	for i := 0; i < len(phoneNumber)-1; i++ {
		pair := phoneNumber[i : i+2]
		meaning, err := h.Repo.FindMeaningByPair(pair)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Database error while fetching pair meaning"})
			return
		}
		analysis.Pairs = append(analysis.Pairs, *meaning)
		analysis.TotalScore += meaning.Points
	}

	// Determine grade based on total score (example logic)
	switch {
	case analysis.TotalScore >= 80:
		analysis.Grade = "Excellent"
	case analysis.TotalScore >= 60:
		analysis.Grade = "Good"
	case analysis.TotalScore >= 40:
		analysis.Grade = "Average"
	default:
		analysis.Grade = "Needs Improvement"
	}

	c.JSON(http.StatusOK, analysis)
}
