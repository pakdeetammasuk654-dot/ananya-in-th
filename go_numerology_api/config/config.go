package config

import (
	"log"
	"os"

	"github.com/joho/godotenv"
)

// Config holds the application configuration
type Config struct {
	DatabaseURL string
	ServerPort  string
}

// Load loads the configuration from environment variables
func Load() Config {
	// In a real production environment, you wouldn't use .env files.
	// For this project, we'll allow it for local development.
	err := godotenv.Load()
	if err != nil {
		log.Println("No .env file found, using environment variables")
	}

	dbURL := os.Getenv("DATABASE_URL")
	if dbURL == "" {
		log.Fatal("DATABASE_URL environment variable is required")
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "8080" // Default port
	}

	return Config{
		DatabaseURL: dbURL,
		ServerPort:  ":" + port,
	}
}
