package handlers

import (
	"net/http"
	"time"

	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"ananya-go/internal/utils"

	"github.com/gin-gonic/gin"
)

func GetDressColor(c *gin.Context) {
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

func GetWanPra(c *gin.Context) {
	dateStr := c.Param("wandate")
	t, err := time.Parse("2006-01-02", dateStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"activity": "fail", "message": "invalid date format"})
		return
	}

	isWanPra := utils.IsWanPra(t)
	tomorrow := t.AddDate(0, 0, 1)
	isWanPraTomorrow := utils.IsWanPra(tomorrow)

	auspiciousToday := utils.GetAuspiciousStatus(t)
	auspiciousTomorrow := utils.GetAuspiciousStatus(tomorrow)

	data := gin.H{
		"activity": "wanpra",
		"tomorrow": isWanPraTomorrow,
		"wanpra":   nil,
		"wan_special": gin.H{
			"wan_tongchai":   "0",
			"wan_atipbadee": "0",
		},
		"wan_special_tomorrow": gin.H{
			"wan_tongchai":   "0",
			"wan_atipbadee": "0",
		},
	}

	if isWanPra {
		data["wanpra"] = gin.H{"wanpra_date": dateStr}
	}

	if auspiciousToday.IsTongchai {
		data["wan_special"].(gin.H)["wan_tongchai"] = "1"
	}
	if auspiciousToday.IsAtipbadee {
		data["wan_special"].(gin.H)["wan_atipbadee"] = "1"
	}

	if auspiciousTomorrow.IsTongchai {
		data["wan_special_tomorrow"].(gin.H)["wan_tongchai"] = "1"
	}
	if auspiciousTomorrow.IsAtipbadee {
		data["wan_special_tomorrow"].(gin.H)["wan_atipbadee"] = "1"
	}

	c.JSON(http.StatusOK, data)
}

func GetMiraDo(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")

	var mira models.MiraDo
	if err := config.DB.Preload("Desc").
		Where("activity = ? AND dayx = ? AND dayy = ?", activity, birthday, today).
		First(&mira).Error; err != nil {
		c.JSON(http.StatusOK, nil)
		return
	}

	c.JSON(http.StatusOK, mira)
}

func GetCurrentTime(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{"current_time": time.Now().Format("15:04")})
}
