package main

import (
	"fmt"
	"phone-analyzer/database"
	"phone-analyzer/handlers"
	"log"
	"net/http"
	"os"
)

func main() {
	// --- Database Connection ---
	// It's recommended to use environment variables for database credentials
	dbHost := os.Getenv("DB_HOST")
	dbUser := os.Getenv("DB_USER")
	dbPass := os.Getenv("DB_PASS")
	dbName := os.Getenv("DB_NAME")

	// Check for required environment variables
	requiredVars := []string{"DB_HOST", "DB_USER", "DB_PASS", "DB_NAME"}
	for _, varName := range requiredVars {
		if os.Getenv(varName) == "" {
			log.Fatalf("FATAL: Environment variable %s is not set.", varName)
		}
	}

	db, err := database.ConnectDB(dbHost, dbUser, dbPass, dbName)
	if err != nil {
		log.Fatalf("Could not connect to the database: %v", err)
	}
	defer db.Close()

	// --- HTTP Server Setup ---
	// Register the handler for the /phone/main/{phoneNumber} endpoint
	http.HandleFunc("/phone/main/", handlers.PhoneHandler(db))

	// Start the server
	port := "8080"
	fmt.Printf("Server starting on port %s...\n", port)
	if err := http.ListenAndServe(":"+port, nil); err != nil {
		log.Fatalf("Could not start server: %v", err)
	}
}
