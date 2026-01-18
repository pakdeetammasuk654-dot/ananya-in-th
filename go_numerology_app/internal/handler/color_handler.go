package handler

import (
	"encoding/json"
	"net/http"
	"strconv"
	"strings"

	"github.com/go-chi/chi/v5"
	"github.com/tayap/go_numerology_app/internal/model"
	"github.com/tayap/go_numerology_app/internal/store"
)

type ColorHandler struct {
	colorStore *store.ColorStore
}

func NewColorHandler(colorStore *store.ColorStore) *ColorHandler {
	return &ColorHandler{colorStore: colorStore}
}

func (h *ColorHandler) DressColorHandler(w http.ResponseWriter, r *http.Request) {
	daysStr := chi.URLParam(r, "days")

	// In PHP code, there is a logic to handle 7 digits day string
	if len(daysStr) == 7 {
		daysStr = daysStr[:len(daysStr)-1]
	}

	dayIDStrs := strings.Split(daysStr, "")
	var dayIDs []int
	for _, idStr := range dayIDStrs {
		id, err := strconv.Atoi(idStr)
		if err != nil {
			http.Error(w, "Invalid day ID", http.StatusBadRequest)
			return
		}
		dayIDs = append(dayIDs, id)
	}

	colors, err := h.colorStore.GetDressColorsByDayIDs(r.Context(), dayIDs)
	if err != nil {
		http.Error(w, "Could not fetch colors", http.StatusInternalServerError)
		return
	}

	response := map[string][]model.DressColor{"cloth_color": colors}
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(response)
}
