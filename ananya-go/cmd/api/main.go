package main

import (
	"ananya-go/internal/config"
	"ananya-go/internal/router"
	"log"
	"os"
)

func main() {
	// Initialize database
	config.InitDB()

	// Setup router
	r := router.SetupRouter()

	// Start server
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	log.Printf("Server starting on port %s", port)
	if err := r.Run(":" + port); err != nil {
		log.Fatal("Failed to start server:", err)
	}
}
