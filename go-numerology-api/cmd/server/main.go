package main

import (
	"database/sql"
	"fmt"
	"log"
	"net/http"
	"os"

	"go-numerology-api/internal/handlers"

	"github.com/gin-gonic/gin"
	_ "github.com/lib/pq"
)

func main() {
	// --- Database Connection ---
	// Enforce the use of environment variables for database credentials.
	dbUser := getEnvOrFatal("PG_USER")
	dbPass := getEnvOrFatal("PG_PASSWORD")
	dbName := getEnvOrFatal("PG_DBNAME")
	dbHost := getEnv("PG_HOST", "localhost")
	dbPort := getEnv("PG_PORT", "5432")

	psqlInfo := fmt.Sprintf("host=%s port=%s user=%s password=%s dbname=%s sslmode=disable",
		dbHost, dbPort, dbUser, dbPass, dbName,
	)

	db, err := sql.Open("postgres", psqlInfo)
	if err != nil {
		log.Fatalf("Error connecting to the database: %v", err)
	}
	defer db.Close()

	if err = db.Ping(); err != nil {
		log.Fatalf("Error pinging the database: %v", err)
	}
	fmt.Println("Successfully connected to the database!")

	// --- Initialize Handlers ---
	numerologyHandler := handlers.NewNumerologyHandler(db)
	memberHandler := handlers.NewMemberHandler(db)

	// --- Setup Router ---
	router := gin.Default()

	router.GET("/", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"message": "Welcome to the Numerology API! The service is running.",
		})
	})

	api := router.Group("/api")
	{
		api.POST("/member/register", memberHandler.Register)
		api.POST("/member/login", memberHandler.Login)
		api.GET("/phone/main/:phoneNumber", numerologyHandler.AnalyzePhoneNumber)
	}

	// --- Start Server ---
	log.Println("Starting server on port :8080...")
	if err := router.Run(":8080"); err != nil {
		log.Fatalf("Failed to start server: %v", err)
	}
}

// getEnv gets an environment variable with a default fallback.
func getEnv(key, fallback string) string {
	if value, ok := os.LookupEnv(key); ok {
		return value
	}
	return fallback
}

// getEnvOrFatal gets an environment variable or panics if it's not set.
func getEnvOrFatal(key string) string {
	value, ok := os.LookupEnv(key)
	if !ok {
		log.Fatalf("FATAL ERROR: Environment variable %s is not set.", key)
	}
	return value
}
