package db

import (
	"database/sql"
	"fmt"
	_ "github.com/lib/pq"
	"log"
	"os"
)

// DB holds the database connection pool.
var DB *sql.DB

// getEnv gets an environment variable or returns a default value.
func getEnv(key, fallback string) string {
	if value, ok := os.LookupEnv(key); ok {
		return value
	}
	return fallback
}

// InitDB initializes the database connection using environment variables.
func InitDB() {
	dbUser := getEnv("DB_USER", "tayap")
	dbPassword := getEnv("DB_PASSWORD", "IntelliP24.X")
	dbName := getEnv("DB_NAME", "tayap")
	dbHost := getEnv("DB_HOST", "43.228.85.200")
	dbPort := getEnv("DB_PORT", "5432")

	// Check for essential variables
	if dbUser == "" || dbPassword == "" || dbName == "" || dbHost == "" {
		log.Fatalf("FATAL: Database configuration is missing. Please set DB_USER, DB_PASSWORD, DB_NAME, and DB_HOST environment variables.")
	}

	connStr := fmt.Sprintf("postgres://%s:%s@%s:%s/%s?sslmode=disable",
		dbUser, dbPassword, dbHost, dbPort, dbName)

	var err error
	DB, err = sql.Open("postgres", connStr)
	if err != nil {
		log.Fatalf("Error opening database: %q", err)
	}

	err = DB.Ping()
	if err != nil {
		log.Fatalf("Error connecting to the database: %q", err)
	}

	fmt.Println("Successfully connected to the database!")
}
