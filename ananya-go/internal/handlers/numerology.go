package handlers

import (
	"net/http"
	"ananya-go/internal/models"
	"ananya-go/internal/utils"
	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
	"time"
)

type NumerologyHandler struct {
	DB *gorm.DB
}

func (h *NumerologyHandler) DressColor(c *gin.Context) {
	days := c.Param("days")
	var colors []models.Color

	for _, char := range days {
		var color models.Color
		if err := h.DB.Where("colorid = ?", string(char)).First(&color).Error; err == nil {
			colors = append(colors, color)
		}
	}

	c.JSON(http.StatusOK, gin.H{"cloth_color": colors})
}

func (h *NumerologyHandler) MiraDo(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")

	var result struct {
		models.MiracleDo
		MiraDesc string `json:"mira_desc"`
	}

	err := h.DB.Table("miracledo").
		Select("miracledo.*, miracledo_desc.mira_desc").
		Joins("left join miracledo_desc on miracledo.mira_id = miracledo_desc.mira_id").
		Where("miracledo.activity = ? AND miracledo.dayx = ? AND miracledo.dayy = ?", activity, birthday, today).
		Scan(&result).Error

	if err != nil {
		c.JSON(http.StatusOK, nil)
		return
	}

	c.JSON(http.StatusOK, result)
}

func (h *NumerologyHandler) WanPra(c *gin.Context) {
	wandateStr := c.Param("wandate")
	t, err := time.Parse("2006-01-02", wandateStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid date format"})
		return
	}

	isWanPra := utils.IsWanPra(t)
	isTongchai, isAtipbadee := utils.GetAuspiciousStatus(t)

	tomorrow := t.AddDate(0, 0, 1)
	isWanPraTomorrow := utils.IsWanPra(tomorrow)
	isTongchaiTomorrow, isAtipbadeeTomorrow := utils.GetAuspiciousStatus(tomorrow)

	data := gin.H{
		"activity": "wanpra",
		"tomorrow": isWanPraTomorrow,
		"wanpra":   nil,
		"wan_special": gin.H{
			"wan_tongchai":  boolToString(isTongchai),
			"wan_atipbadee": boolToString(isAtipbadee),
		},
		"wan_special_tomorrow": gin.H{
			"wan_tongchai":  boolToString(isTongchaiTomorrow),
			"wan_atipbadee": boolToString(isAtipbadeeTomorrow),
		},
	}

	if isWanPra {
		data["wanpra"] = gin.H{"wanpra_date": wandateStr}
	}

	c.JSON(http.StatusOK, data)
}

func boolToString(b bool) string {
	if b {
		return "1"
	}
	return "0"
}
