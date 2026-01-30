package handlers

import (
	"go-ananya/internal/models"
	"go-ananya/pkg/utils"
	"net/http"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

type PredictionHandler struct {
	db *gorm.DB
}

func NewPredictionHandler(db *gorm.DB) *PredictionHandler {
	return &PredictionHandler{db: db}
}

func (h *PredictionHandler) MiraDo(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")

	var result models.MiraDo
	err := h.db.Where("activity = ? AND dayx = ? AND dayy = ?", activity, birthday, today).First(&result).Error
	if err != nil {
		c.JSON(http.StatusOK, nil)
		return
	}

	c.JSON(http.StatusOK, result)
}

func (h *PredictionHandler) MiraDoV2(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")
	currentday := c.Param("currentday")

	var prediction models.MiraDo
	err := h.db.Where("activity = ? AND dayx = ? AND dayy = ?", activity, birthday, today).First(&prediction).Error
	if err != nil {
		c.JSON(http.StatusOK, nil)
		return
	}

	isWanpra := utils.IsWanPra(currentday)

	c.JSON(http.StatusOK, gin.H{
		"wanpra": isWanpra,
		"domira": prediction,
	})
}

func (h *PredictionHandler) DressColor(c *gin.Context) {
	days := c.Param("days")
	var colors []models.Color

	for _, char := range days {
		var color models.Color
		if err := h.db.Where("colorid = ?", string(char)).First(&color).Error; err == nil {
			colors = append(colors, color)
		}
	}

	c.JSON(http.StatusOK, gin.H{
		"cloth_color": colors,
	})
}

func (h *PredictionHandler) BagColor(c *gin.Context) {
	memberID := c.Param("memberid")
	age1 := c.Param("age1")
	age2 := c.Param("age2")

	var bagColors []models.BagColor
	h.db.Where("memberid = ? AND (age = ? OR age = ?)", memberID, age1, age2).Find(&bagColors)

	c.JSON(http.StatusOK, gin.H{
		"activity":        "success",
		"member_bagcolor": bagColors,
	})
}
