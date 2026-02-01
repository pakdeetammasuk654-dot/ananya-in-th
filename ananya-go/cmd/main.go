package main

import (
	"ananya-go/internal/config"
	"ananya-go/internal/handlers"
	"fmt"
	"os"

	"github.com/gin-gonic/gin"
)

func main() {
	// Initialize database
	config.InitDB()

	r := gin.Default()

	// Auth routes
	auth := r.Group("/api/auth")
	{
		auth.POST("/login", handlers.Login)
		auth.POST("/register", handlers.Register)
	}

	// Member routes
	member := r.Group("/member")
	{
		member.GET("/mirado/:activity/:birthday/:today", handlers.MiraDo)
		member.GET("/dresscolor/:days", handlers.DressColor)
		member.GET("/currenttime", handlers.CurrentTime)
	}

	// Article routes
	admin := r.Group("/admin")
	{
		admin.GET("/articles", handlers.ListArticles)
		admin.POST("/articles/save", handlers.SaveArticle)
		admin.DELETE("/articles/:id", handlers.DeleteArticle)
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	fmt.Printf("Server starting on port %s...\n", port)
	r.Run(":" + port)
}
