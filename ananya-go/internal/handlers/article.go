package handlers

import (
	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

func ListArticles(c *gin.Context) {
	var articles []models.Article
	if err := config.DB.Order("art_id DESC").Find(&articles).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, articles)
}

func SaveArticle(c *gin.Context) {
	var req models.Article
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	if req.PublishedAt == nil {
		now := time.Now()
		req.PublishedAt = &now
	}

	if req.ArtID != 0 {
		// Update
		if err := config.DB.Save(&req).Error; err != nil {
			c.JSON(http.StatusOK, gin.H{"status": "fail"})
			return
		}
	} else {
		// Create
		if err := config.DB.Create(&req).Error; err != nil {
			c.JSON(http.StatusOK, gin.H{"status": "fail"})
			return
		}
	}

	c.JSON(http.StatusOK, gin.H{"status": "success"})
}

func DeleteArticle(c *gin.Context) {
	id := c.Param("id")
	if err := config.DB.Delete(&models.Article{}, id).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{"status": "fail"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success"})
}
