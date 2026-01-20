package handlers

import (
	"database/sql"
	"go-numerology-api/internal/database"
	"go-numerology-api/internal/services"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
)

var (
	// This will act as a simple in-memory cache for the number meanings
	numerologyService *services.NumerologyService
)
// NumerologyHandler holds the dependencies for the numerology handlers
type NumerologyHandler struct {
    DB *sql.DB
}

// NewNumerologyHandler creates a new NumerologyHandler
func NewNumerologyHandler(db *sql.DB) *NumerologyHandler {
    // Load number meanings into the cache on startup
    if numerologyService == nil {
        meanings, err := database.GetNumberMeanings(db)
        if err != nil {
            // In a real application, you might want to handle this more gracefully
            // For now, we'll log a fatal error if the meanings can't be loaded.
            log.Fatalf("Could not load number meanings: %v", err)
        }
        numerologyService = services.NewNumerologyService(meanings)
        log.Println("Successfully loaded and cached number meanings.")
    }
    return &NumerologyHandler{DB: db}
}

// AnalyzePhoneNumber handles the phone number analysis request
func (h *NumerologyHandler) AnalyzePhoneNumber(c *gin.Context) {
	phoneNumber := c.Param("phoneNumber")

	if numerologyService == nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Numerology service not initialized"})
		return
	}

	result := numerologyService.AnalyzePhoneNumber(phoneNumber)
	if result == nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid phone number provided"})
		return
	}

	c.JSON(http.StatusOK, result)
}
