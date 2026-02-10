package handlers

import (
	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

func MiraDo(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")

	if birthday == "" {
		c.JSON(http.StatusOK, nil)
		return
	}

	var result struct {
		models.MiracleDo
		MiraDesc string `json:"mira_desc"`
	}

	err := config.DB.Table("miracledo").
		Select("miracledo.*, miracledo_desc.mira_desc").
		Joins("LEFT JOIN miracledo_desc ON miracledo.mira_id = CAST(miracledo_desc.mira_id AS varchar)").
		Where("miracledo.activity = ? AND miracledo.dayx = ? AND miracledo.dayy = ?", activity, birthday, today).
		Scan(&result).Error

	if err != nil || result.MiraID == 0 {
		c.JSON(http.StatusOK, nil)
		return
	}

	c.JSON(http.StatusOK, result)
}

func DressColor(c *gin.Context) {
	days := c.Param("days")
	var colors []models.Color

	for _, char := range days {
		var color models.Color
		if err := config.DB.Where("colorid = ?", string(char)).First(&color).Error; err == nil {
			colors = append(colors, color)
		}
	}

	c.JSON(http.StatusOK, gin.H{"cloth_color": colors})
}

func CurrentTime(c *gin.Context) {
	loc, _ := time.LoadLocation("Asia/Bangkok")
	now := time.Now().In(loc)
	c.JSON(http.StatusOK, gin.H{"current_time": now.Format("15:04")})
}
