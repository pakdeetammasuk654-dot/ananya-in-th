package handlers

import (
	"ananya-go/internal/utils"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

func GetCalendarInfo(c *gin.Context) {
	dateStr := c.Query("date")
	var t time.Time
	var err error
	if dateStr != "" {
		t, err = time.Parse("2006-01-02", dateStr)
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid date format. Use YYYY-MM-DD"})
			return
		}
	} else {
		t = time.Now()
	}

	lunar := utils.GetThaiLunarDate(t)
	isWanPra := utils.IsWanPra(t)

	c.JSON(http.StatusOK, gin.H{
		"date":       t.Format("2006-01-02"),
		"lunar":      lunar,
		"is_wan_pra": isWanPra,
	})
}

func AnalyzeNameHandler(c *gin.Context) {
	name := c.Query("name")
	if name == "" {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Name parameter is required"})
		return
	}

	sum := utils.AnalyzeName(name)
	c.JSON(http.StatusOK, gin.H{
		"name": name,
		"sum":  sum,
	})
}
