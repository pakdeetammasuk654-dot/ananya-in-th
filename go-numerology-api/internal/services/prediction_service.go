package services

import (
	"database/sql"
	"fmt"
	"strings"
)

// PredictionService handles business logic for predictions.
type PredictionService struct {
	DB *sql.DB
}

// Color represents a color from the colortb table.
type Color struct {
	ColorID    int    `json:"colorid"`
	DayEng     string `json:"day_eng"`
	ColorCode1 string `json:"color_code1"`
	ColorCode2 string `json:"color_code2"`
	ColorCode3 string `json:"color_code3"`
	ColorCode4 string `json:"color_code4"`
}

// GetDressColors retrieves auspicious dress colors based on day IDs.
func (s *PredictionService) GetDressColors(dayIDs []string) ([]Color, error) {
	if len(dayIDs) == 0 {
		return []Color{}, nil
	}

	// Create placeholders for the IN clause: $1, $2, $3, ...
	placeholders := make([]string, len(dayIDs))
	args := make([]interface{}, len(dayIDs))
	for i, id := range dayIDs {
		placeholders[i] = fmt.Sprintf("$%d", i+1)
		args[i] = id
	}

	query := fmt.Sprintf(
		"SELECT colorid, day_eng, color_code1, color_code2, color_code3, color_code4 FROM colortb WHERE colorid IN (%s)",
		strings.Join(placeholders, ","),
	)

	rows, err := s.DB.Query(query, args...)
	if err != nil {
		return nil, fmt.Errorf("failed to query colors: %w", err)
	}
	defer rows.Close()

	var colors []Color
	for rows.Next() {
		var color Color
		if err := rows.Scan(&color.ColorID, &color.DayEng, &color.ColorCode1, &color.ColorCode2, &color.ColorCode3, &color.ColorCode4); err != nil {
			return nil, fmt.Errorf("failed to scan color row: %w", err)
		}
		colors = append(colors, color)
	}

	if err = rows.Err(); err != nil {
		return nil, fmt.Errorf("error iterating color rows: %w", err)
	}

	return colors, nil
}
