package main

import (
	"context"
	"log"
	"net/http"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
	"github.com/tayap/go_numerology_app/internal/handler"
	"github.com/tayap/go_numerology_app/internal/store"
)

func main() {
	// Connect to the database
	conn, err := store.NewDB()
	if err != nil {
		log.Fatalf("could not connect to database: %v", err)
	}
	defer conn.Close(context.Background())
	log.Println("Successfully connected to the database")

	r := chi.NewRouter()
	r.Use(middleware.Logger)

	// Initialize stores and handlers
	colorStore := store.NewColorStore(conn)
	colorHandler := handler.NewColorHandler(colorStore)

	// Define routes
	r.Get("/", func(w http.ResponseWriter, r *http.Request) {
		w.Write([]byte("Welcome to Go Numerology App"))
	})
	r.Get("/dresscolor/{days}", colorHandler.DressColorHandler)

	log.Println("Starting server on :8080")
	if err := http.ListenAndServe(":8080", r); err != nil {
		log.Fatalf("could not start server: %v", err)
	}
}
