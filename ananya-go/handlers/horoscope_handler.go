package handlers

import (
	"ananya-go/db"
	"encoding/json"
	"net/http"
	"strings"
)

type Color struct {
	ColorID    int    `json:"colorid"`
	DayEng     string `json:"day_eng"`
	ColorCode1 string `json:"color_code1"`
	ColorCode2 string `json:"color_code2"`
	ColorCode3 string `json:"color_code3"`
	ColorCode4 string `json:"color_code4"`
}

func CalculateDressColor(w http.ResponseWriter, r *http.Request) {
	daysParam := r.URL.Query().Get("days")
	if daysParam == "" {
		http.Error(w, "Missing 'days' query parameter", http.StatusBadRequest)
		return
	}

	var colors []Color
	dayIDs := strings.Split(daysParam, "")

	for _, dayID := range dayIDs {
		var color Color
		sqlStatement := `SELECT colorid, day_eng, color_code1, color_code2, color_code3, color_code4 FROM colortb WHERE colorid=$1`
		err := db.DB.QueryRow(sqlStatement, dayID).Scan(
			&color.ColorID, &color.DayEng, &color.ColorCode1, &color.ColorCode2, &color.ColorCode3, &color.ColorCode4,
		)

		if err != nil {
			// In a real app, you might want to decide whether to stop or just log the error and continue.
			// For simplicity, we'll skip days that are not found.
			continue
		}
		colors = append(colors, color)
	}

	response := map[string][]Color{"cloth_color": colors}
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(response)
}
