package handlers

import (
	"ananya-go/internal/config"
	"ananya-go/internal/models"
	"net/http"
	"strconv"

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

func GetArticle(c *gin.Context) {
	id := c.Param("id")
	var article models.Article
	if err := config.DB.First(&article, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Article not found"})
		return
	}
	c.JSON(http.StatusOK, article)
}

func CreateArticle(c *gin.Context) {
	var article models.Article
	if err := c.ShouldBindJSON(&article); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}
	if err := config.DB.Create(&article).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusCreated, article)
}

func UpdateArticle(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid article ID"})
		return
	}
	var article models.Article
	if err := config.DB.First(&article, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Article not found"})
		return
	}
	if err := c.ShouldBindJSON(&article); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}
	article.ArtID = id // ensure ID is preserved
	if err := config.DB.Save(&article).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Could not update article"})
		return
	}
	c.JSON(http.StatusOK, article)
}

func DeleteArticle(c *gin.Context) {
	id := c.Param("id")
	if err := config.DB.Delete(&models.Article{}, id).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success"})
}

// Support for legacy topictb
func ListTopics(c *gin.Context) {
	var topics []models.Topic
	if err := config.DB.Order("topic_id DESC").Find(&topics).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Could not fetch topics"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"topic_list": topics})
}
