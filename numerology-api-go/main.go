package main

import (
	"context"
	"log"
	"numerology-api-go/configs"
	"numerology-api-go/handlers"
	"numerology-api-go/routes"

	"github.com/gin-gonic/gin"
)

func main() {
	// สร้าง Connection Pool
	pool, err := configs.CreateDBPool()
	if err != nil {
		log.Fatalf("Failed to create database pool: %v", err)
	}
	defer pool.Close()

	// Ping database to ensure connection is valid
	if err := pool.Ping(context.Background()); err != nil {
		log.Fatalf("Failed to ping database: %v", err)
	}

	// กำหนด pool ให้กับ package handlers
	handlers.DB = pool

	// เริ่ม Gin server
	r := gin.Default()

	// Setup routes
	routes.SetupRoutes(r)

	r.Run() // listen and serve on 0.0.0.0:8080
}
