package main

import (
	"go-numerology-api/config"
	"go-numerology-api/handlers"
	"go-numerology-api/repository"
	"log"

	"github.com/gin-gonic/gin"
)

func main() {
	// Load configuration
	cfg := config.Load()

	// Connect to the database
	db, err := repository.ConnectDB(cfg.DatabaseURL)
	if err != nil {
		log.Fatalf("Could not connect to the database: %v", err)
	}
	defer db.Close()

	// Set up repositories
	memberRepo := repository.NewMemberRepository(db)
	numberRepo := repository.NewNumberRepository(db)

	// Set up handlers
	memberHandler := handlers.NewMemberHandler(memberRepo)
	phoneHandler := handlers.NewPhoneHandler(numberRepo)

	// Set up router
	router := gin.Default()

	// Register routes
	api := router.Group("/api/v1") // Grouping routes for versioning
	{
		api.POST("/member/register", memberHandler.RegisterMember)
		api.GET("/phone/analyze/:phoneNumber", phoneHandler.AnalyzePhoneNumber)
	}

	// Start the server
	log.Printf("Server starting on port %s", cfg.ServerPort)
	if err := router.Run(cfg.ServerPort); err != nil {
		log.Fatalf("Failed to run server: %v", err)
	}
}
