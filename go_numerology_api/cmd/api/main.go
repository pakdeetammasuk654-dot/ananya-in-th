package main

import (
	"log"
	"net/http"

	"go_numerology_api/internal/database"
	"go_numerology_api/internal/handlers"
)

func main() {
	// Initialize the database connection
	database.InitDB()
	// Ensure the database connection is closed when the application exits
	defer database.DB.Close()

	// Create a new router
	mux := http.NewServeMux()

	// --- Register API routes ---
	mux.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Content-Type", "application/json")
		w.Write([]byte(`{"message": "Welcome to the Numerology API!"}`))
	})
	mux.HandleFunc("/register", handlers.RegisterUser)
	mux.HandleFunc("/login", handlers.LoginUser)


	log.Println("Starting server on port 8080...")
	// Start the server with the new router
	if err := http.ListenAndServe(":8080", mux); err != nil {
		log.Fatalf("Could not start server: %s\n", err)
	}
}
