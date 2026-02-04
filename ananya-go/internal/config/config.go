package config

import (
	"fmt"
	"os"
)

type Config struct {
	DBURL string
}

func LoadConfig() Config {
	dbUser := getEnv("DB_USER", "tayap")
	dbPass := os.Getenv("DB_PASS") // Must be set via env
	dbName := getEnv("DB_NAME", "tayap")
	dbHost := getEnv("DB_HOST", "localhost")
	dbPort := getEnv("DB_PORT", "5432")

	if dbPass == "" {
		fmt.Println("WARNING: DB_PASS is not set")
	}

	return Config{
		DBURL: fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%s sslmode=disable TimeZone=Asia/Bangkok",
			dbHost, dbUser, dbPass, dbName, dbPort),
	}
}

func getEnv(key, fallback string) string {
	if value, ok := os.LookupEnv(key); ok {
		return value
	}
	return fallback
}
