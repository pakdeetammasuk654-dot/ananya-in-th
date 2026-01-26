package handlers

import (
	"database/sql"
	"go-numerology-api/internal/services"
	"net/http"
	"strings"

	"github.com/gin-gonic/gin"
)

// PredictionHandler holds the prediction service.
type PredictionHandler struct {
	Service *services.PredictionService
}

// NewPredictionHandler creates a new PredictionHandler.
func NewPredictionHandler(db *sql.DB) *PredictionHandler {
	return &PredictionHandler{
		Service: &services.PredictionService{DB: db},
	}
}

// DressColor handles the dress color prediction request.
func (h *PredictionHandler) DressColor(c *gin.Context) {
	// The day IDs are passed as a single string like "123"
	daysParam := c.Param("days")
	if daysParam == "" {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Day IDs are required"})
		return
	}

	// Split the string into individual day IDs
	dayIDs := strings.Split(daysParam, "")

	colors, err := h.Service.GetDressColors(dayIDs)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to get dress colors"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"cloth_color": colors})
}
