package store

import (
	"context"

	"github.com/jackc/pgx/v5"
	"github.com/tayap/go_numerology_app/internal/model"
)

type ColorStore struct {
	db *pgx.Conn
}

func NewColorStore(db *pgx.Conn) *ColorStore {
	return &ColorStore{db: db}
}

func (s *ColorStore) GetDressColorsByDayIDs(ctx context.Context, dayIDs []int) ([]model.DressColor, error) {
	rows, err := s.db.Query(ctx, "SELECT colorid, day_eng, color_code1, color_code2, color_code3, color_code4 FROM colortb WHERE colorid = ANY($1)", dayIDs)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var colors []model.DressColor
	for rows.Next() {
		var c model.DressColor
		if err := rows.Scan(&c.ColorID, &c.DayEng, &c.ColorCode1, &c.ColorCode2, &c.ColorCode3, &c.ColorCode4); err != nil {
			return nil, err
		}
		colors = append(colors, c)
	}

	return colors, nil
}
