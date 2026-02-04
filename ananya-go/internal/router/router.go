package router

import (
	"ananya-go/internal/handlers"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

func SetupRouter(db *gorm.DB) *gin.Engine {
	r := gin.Default()

	authHandler := handlers.NewAuthHandler(db)
	adminHandler := handlers.NewAdminHandler(db)
	astroHandler := handlers.NewAstroHandler(db)

	admin := r.Group("/admin")
	{
		admin.GET("/topic", adminHandler.TopicList)
		admin.GET("/articles/list", adminHandler.ArticleList)
	}

	member := r.Group("/member")
	{
		member.POST("/login", authHandler.Login)
		member.POST("/register", authHandler.Register)
		member.GET("/mirado/:activity/:birthday/:today", astroHandler.MiraDo)
		member.GET("/dresscolor/:days", astroHandler.DressColor)
		member.GET("/wanpra/:wandate", astroHandler.WanPra)
	}

	return r
}
