package handlers

import (
	"net/http"

	"ananya-go/internal/config"
	"ananya-go/internal/models"

	"github.com/gin-gonic/gin"
)

func ListArticles(c *gin.Context) {
	var articles []models.Article
	if err := config.DB.Where("is_published = ?", true).Order("published_at desc").Find(&articles).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch articles"})
		return
	}

	c.JSON(http.StatusOK, articles)
}

func GetArticle(c *gin.Context) {
	slug := c.Param("slug")
	var article models.Article
	if err := config.DB.Where("slug = ? AND is_published = ?", slug, true).First(&article).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Article not found"})
		return
	}

	c.JSON(http.StatusOK, article)
}
