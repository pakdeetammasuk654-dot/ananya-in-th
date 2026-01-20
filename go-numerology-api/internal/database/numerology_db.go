package database

import (
	"database/sql"
	"go-numerology-api/internal/models"
	"log"
)

// GetNumberMeanings retrieves all number meanings from the database
// and returns them as a map where the key is the pair number (e.g., "15", "42")
func GetNumberMeanings(db *sql.DB) (map[string]models.NumberMeaning, error) {
	rows, err := db.Query("SELECT pairnumber, pairpoint, miracledesc, miracledetail FROM numbers")
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	meanings := make(map[string]models.NumberMeaning)
	for rows.Next() {
		var meaning models.NumberMeaning
		var pairNumber string
		if err := rows.Scan(&pairNumber, &meaning.PairPoint, &meaning.MiracleDesc, &meaning.MiracleDetail); err != nil {
			log.Printf("Error scanning number meaning: %v", err)
			continue
		}
		meanings[pairNumber] = meaning
	}

	return meanings, nil
}
