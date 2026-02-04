package handlers

import (
	"ananya-go/internal/models"
	"ananya-go/internal/utils"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

type AstroHandler struct {
	DB *gorm.DB
}

func NewAstroHandler(db *gorm.DB) *AstroHandler {
	return &AstroHandler{DB: db}
}

func (h *AstroHandler) MiraDo(c *gin.Context) {
	activity := c.Param("activity")
	birthday := c.Param("birthday")
	today := c.Param("today")

	var result map[string]interface{}
	sql := `SELECT miracledo.*, miracledo_desc.mira_desc
	        FROM miracledo
	        LEFT JOIN miracledo_desc ON miracledo.mira_id = CAST(miracledo_desc.mira_id AS varchar)
	        WHERE miracledo.activity = ? AND miracledo.dayx = ? AND miracledo.dayy = ?`

	if err := h.DB.Raw(sql, activity, birthday, today).Scan(&result).Error; err != nil {
		c.JSON(http.StatusOK, nil)
		return
	}

	if len(result) == 0 {
		c.JSON(http.StatusOK, nil)
		return
	}

	c.JSON(http.StatusOK, result)
}

func (h *AstroHandler) DressColor(c *gin.Context) {
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

func (h *AstroHandler) WanPra(c *gin.Context) {
	wandate := c.Param("wandate")
	if wandate == "" {
		c.JSON(http.StatusOK, gin.H{"activity": "fail"})
		return
	}

	date, err := time.Parse("2006-01-02", wandate)
	if err != nil {
		c.JSON(http.StatusOK, gin.H{"activity": "fail"})
		return
	}

	tomorrow := date.AddDate(0, 0, 1).Format("2006-01-02")
	isWanpra := utils.IsWanPra(wandate)
	isWanpraTomorrow := utils.IsWanPra(tomorrow)

	status := utils.GetAuspiciousStatus(wandate)
	statusTomorrow := utils.GetAuspiciousStatus(tomorrow)

	data := gin.H{
		"activity": "wanpra",
		"tomorrow": isWanpraTomorrow,
		"wanpra":   nil,
		"wan_special": gin.H{
			"wan_tongchai":   formatBool(status.IsTongchai),
			"wan_atipbadee": formatBool(status.IsAtipbadee),
		},
		"wan_special_tomorrow": gin.H{
			"wan_tongchai":   formatBool(statusTomorrow.IsTongchai),
			"wan_atipbadee": formatBool(statusTomorrow.IsAtipbadee),
		},
	}

	if isWanpra {
		data["wanpra"] = gin.H{"wanpra_date": wandate}
	}

	c.JSON(http.StatusOK, data)
}

func formatBool(b bool) string {
	if b {
		return "1"
	}
	return "0"
}
