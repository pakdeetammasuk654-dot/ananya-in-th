package main

import (
	"ananya-go/db"
	"ananya-go/handlers"
	"fmt"
	"log"
	"net/http"
)

func main() {
	// Initialize database connection
	db.InitDB()

	// API routes
	http.HandleFunc("/register", handlers.Register)
	http.HandleFunc("/login", handlers.Login)
	http.HandleFunc("/dresscolor", handlers.CalculateDressColor)

	// Start the server
	port := "8081" // Using a different port from the PHP app
	fmt.Printf("Server starting on port %s\n", port)
	if err := http.ListenAndServe(":"+port, nil); err != nil {
		log.Fatalf("Could not start server: %s\n", err)
	}
}
