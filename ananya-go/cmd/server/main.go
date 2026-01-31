package main

import (
	"log"
	"ananya-go/internal/config"
	"ananya-go/internal/handlers"
	"github.com/gin-gonic/gin"
)

func main() {
	db, err := config.ConnectDB()
	if err != nil {
		log.Fatal("Failed to connect to database:", err)
	}

	r := gin.Default()

	authHandler := &handlers.AuthHandler{DB: db}
	articleHandler := &handlers.ArticleHandler{DB: db}
	numHandler := &handlers.NumerologyHandler{DB: db}

	r.POST("/member/login", authHandler.Login)
	r.POST("/member/register", authHandler.Register)

	r.GET("/articles", articleHandler.List)
	r.GET("/articles/:slug", articleHandler.GetBySlug)
	r.POST("/admin/articles", articleHandler.Create)
	r.PUT("/admin/articles/:id", articleHandler.Update)
	r.DELETE("/admin/articles/:id", articleHandler.Delete)

	r.GET("/member/dresscolor/:days", numHandler.DressColor)
	r.GET("/member/mirado/:activity/:birthday/:today", numHandler.MiraDo)
	r.GET("/member/wanpra/:wandate", numHandler.WanPra)

	r.Run(":8080")
}
