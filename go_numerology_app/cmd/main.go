package main

import (
	"log"
	"net/http"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
	"github.com/tayap/numerology/internal/handler"
	"github.com/tayap/numerology/internal/store"
)

func main() {
	// Initialize database connection
	// The application will exit if it cannot connect to the database.
	dbpool, err := store.NewConnection()
	if err != nil {
		log.Fatalf("Could not connect to the database: %v", err)
	}
	defer dbpool.Close()

	log.Println("Successfully connected to the database!")

	// Initialize router
	r := chi.NewRouter()

	// Add middleware (e.g., for logging, recovery)
	r.Use(middleware.Logger)
	r.Use(middleware.Recoverer)

	// --- Define API Routes ---
	// We will mirror the structure from the original PHP project.
	r.Route("/member", func(r chi.Router) {
		// Corresponds to: $app->post('/login', 'App\Managers\UserController:userLogin');
		r.Post("/login", handler.UserLogin)
		// Other member routes will be added here...
	})

	// Add a simple root handler for health check
	r.Get("/", func(w http.ResponseWriter, r *http.Request) {
		w.Write([]byte("Numerology Go App is running."))
	})

	// Start the server
	port := "8081"
	log.Printf("Starting server on :%s", port)
	if err := http.ListenAndServe(":"+port, r); err != nil {
		log.Fatalf("Failed to start server: %v", err)
	}
}
