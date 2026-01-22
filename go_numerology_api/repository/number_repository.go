package repository

import (
	"database/sql"
	"go-numerology-api/models"
)

// NumberRepository handles database operations for number meanings
type NumberRepository struct {
	DB *sql.DB
}

// NewNumberRepository creates a new NumberRepository
func NewNumberRepository(db *sql.DB) *NumberRepository {
	return &NumberRepository{DB: db}
}

// FindMeaningByPair finds the meaning of a number pair from the database
func (r *NumberRepository) FindMeaningByPair(pair string) (*models.PairMeaning, error) {
	query := `
		SELECT pairnumber, miracledesc, miracledetail, pairpoint
		FROM numbers
		WHERE pairnumber = $1
	`
	meaning := &models.PairMeaning{Pair: pair}
	err := r.DB.QueryRow(query, pair).Scan(
		&meaning.Pair,
		&meaning.Meaning,
		&meaning.Description,
		&meaning.Points,
	)

	if err != nil {
		if err == sql.ErrNoRows {
			// Return a default meaning if the pair is not found
			return &models.PairMeaning{
				Pair:        pair,
				Meaning:     "Not Found",
				Description: "No specific meaning found for this pair.",
				Points:      0,
			}, nil
		}
		return nil, err
	}

	return meaning, nil
}
