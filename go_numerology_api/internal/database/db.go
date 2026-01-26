package database

import (
	"database/sql"
	"fmt"
	"log"
	"os"
	"strconv"

	_ "github.com/lib/pq" // PostgreSQL driver
)

// DB is a connection handle to the database
var DB *sql.DB

// InitDB initializes the database connection using environment variables.
func InitDB() {
	// --- Get database configuration from environment variables ---
	host := getEnvOrFatal("PGHOST")
	portStr := getEnvOrFatal("PGPORT")
	user := getEnvOrFatal("PGUSER")
	password := getEnvOrFatal("PGPASSWORD")
	dbname := getEnvOrFatal("PGDATABASE")

	port, err := strconv.Atoi(portStr)
	if err != nil {
		log.Fatalf("Invalid port value for PGPORT: %s. Must be an integer.", portStr)
	}

	// Create the connection string
	psqlInfo := fmt.Sprintf("host=%s port=%d user=%s password=%s dbname=%s sslmode=require",
		host, port, user, password, dbname)

	// Open a connection to the database
	DB, err = sql.Open("postgres", psqlInfo)
	if err != nil {
		log.Fatalf("Error opening database connection: %q", err)
	}

	if err = DB.Ping(); err != nil {
		log.Fatalf("Error connecting to the database: %q", err)
	}

	log.Println("Successfully connected to the database!")
}

// getEnvOrFatal retrieves an environment variable by key or panics if it's not set.
func getEnvOrFatal(key string) string {
	value, ok := os.LookupEnv(key)
	if !ok {
		log.Fatalf("FATAL: Environment variable %s is not set.", key)
	}
	return value
}
