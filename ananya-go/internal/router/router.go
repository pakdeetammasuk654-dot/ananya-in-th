package router

import (
	"ananya-go/internal/handlers"

	"github.com/gin-gonic/gin"
)

func SetupRouter() *gin.Engine {
	r := gin.Default()

	// Member routes
	member := r.Group("/member")
	{
		member.POST("/login", handlers.Login)
		member.POST("/register", handlers.Register)
		member.GET("/dresscolor/:days", handlers.GetDressColor)
		member.GET("/wanpra/:wandate", handlers.GetWanPra)
		member.GET("/currenttime", handlers.GetCurrentTime)
		member.GET("/mirado/:activity/:birthday/:today", handlers.GetMiraDo)
	}

	// Article routes
	articles := r.Group("/articles")
	{
		articles.GET("", handlers.ListArticles)
		articles.GET("/:slug", handlers.GetArticle)
	}

	return r
}
