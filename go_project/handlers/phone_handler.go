package handlers

import (
	"database/sql"
	"encoding/json"
	"phone-analyzer/logic"
	"net/http"
	"regexp"
	"strings"
)

// PhoneHandler handles the HTTP requests for phone number analysis.
func PhoneHandler(db *sql.DB) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		// Extract phone number from URL path
		parts := strings.Split(r.URL.Path, "/")
		if len(parts) < 4 {
			http.Error(w, "Invalid URL format", http.StatusBadRequest)
			return
		}
		phoneNumber := parts[3]

		// Validate phone number: must be 10 digits
		match, _ := regexp.MatchString(`^[0-9]{10}$`, phoneNumber)
		if !match {
			http.Error(w, "ERROR PHONE NUMBER!!! Invalid phone number format. It must be 10 digits.", http.StatusBadRequest)
			return
		}

		// Call the analysis logic
		result, err := logic.AnalyzePhoneNumber(phoneNumber, db)
		if err != nil {
			http.Error(w, "Error analyzing phone number: "+err.Error(), http.StatusInternalServerError)
			return
		}

		// Send the result as JSON response
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(result)
	}
}
