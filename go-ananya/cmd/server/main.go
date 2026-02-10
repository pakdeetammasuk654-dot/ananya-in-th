package main

import (
	"go-ananya/internal/handlers"
	"go-ananya/internal/repository"
	"log"
	"os"

	"github.com/gin-gonic/gin"
)

func main() {
	db, err := repository.InitDB()
	if err != nil {
		log.Fatal("Failed to connect to database:", err)
	}

	r := gin.Default()

	userRepo := repository.NewUserRepository(db)
	articleRepo := repository.NewArticleRepository(db)

	authHandler := handlers.NewAuthHandler(userRepo)
	articleHandler := handlers.NewArticleHandler(articleRepo)
	predictionHandler := handlers.NewPredictionHandler(db)

	// Routes
	member := r.Group("/member")
	{
		member.POST("/login", authHandler.Login)
		member.POST("/register", authHandler.Register)
		member.GET("/mirado/:activity/:birthday/:today", predictionHandler.MiraDo)
		member.GET("/miradoV2/:activity/:birthday/:today/:currentday", predictionHandler.MiraDoV2)
		member.GET("/dresscolor/:days", predictionHandler.DressColor)
		member.GET("/bagcolor/:memberid/:age1/:age2", predictionHandler.BagColor)
	}

	news := r.Group("/news")
	{
		news.GET("/topicall/:newsidtype", articleHandler.List)
		news.GET("/topic24", articleHandler.NewsTop24)
		news.GET("/topicdetail/:number", articleHandler.Detail)
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}
	r.Run(":" + port)
}
