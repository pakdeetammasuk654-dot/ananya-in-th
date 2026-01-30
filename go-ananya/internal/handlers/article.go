package handlers

import (
	"go-ananya/internal/repository"
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
)

type ArticleHandler struct {
	repo *repository.ArticleRepository
}

func NewArticleHandler(repo *repository.ArticleRepository) *ArticleHandler {
	return &ArticleHandler{repo: repo}
}

func (h *ArticleHandler) List(c *gin.Context) {
	articles, err := h.repo.ListPublished(50)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, articles)
}

func (h *ArticleHandler) NewsTop24(c *gin.Context) {
	articles, err := h.repo.ListPublished(20)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	// Grouping logic from PHP NewsController:newsTop24
	dataHot := articles
	if len(articles) > 7 {
		dataHot = articles[:7]
	}

	limit4 := articles
	if len(articles) > 4 {
		limit4 = articles[:4]
	}

	c.JSON(http.StatusOK, gin.H{
		"news_hot":      dataHot,
		"news_feedback": limit4,
		"news_phonenum": limit4,
		"news_namesur":  limit4,
		"news_tabian":   limit4,
		"news_homenum":  limit4,
		"news_concept":  limit4,
	})
}

func (h *ArticleHandler) Detail(c *gin.Context) {
	idStr := c.Param("number")
	id, _ := strconv.Atoi(idStr)
	article, err := h.repo.FindByID(id)
	if err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Article not found"})
		return
	}
	c.JSON(http.StatusOK, article)
}
