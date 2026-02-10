package router

import (
	"ananya-go/internal/handlers"
	"github.com/gin-gonic/gin"
)

func SetupRouter() *gin.Engine {
	r := gin.Default()

	// Auth routes
	auth := r.Group("/auth")
	{
		auth.POST("/login", handlers.Login)
		auth.POST("/register", handlers.Register)
	}

	// Article routes
	articles := r.Group("/articles")
	{
		articles.GET("/", handlers.ListArticles)
		articles.GET("/:id", handlers.GetArticle)
		articles.POST("/", handlers.CreateArticle)
		articles.PUT("/:id", handlers.UpdateArticle)
		articles.DELETE("/:id", handlers.DeleteArticle)
	}

	// Legacy topic routes
	r.GET("/topics", handlers.ListTopics)

	// Miracle/Calendar routes
	r.GET("/calendar", handlers.GetCalendarInfo)
	r.GET("/analyze-name", handlers.AnalyzeNameHandler)

	return r
}
