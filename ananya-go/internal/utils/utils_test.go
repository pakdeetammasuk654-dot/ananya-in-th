package utils

import (
	"testing"
	"time"
)

func TestGetThaiLunarDate(t *testing.T) {
	// 1 Jan 2023 was Waxing 10, Month 2, BE 2566
	refDate := time.Date(2023, 1, 1, 0, 0, 0, 0, time.UTC)
	lunar := GetThaiLunarDate(refDate)

	if lunar.Day != 10 || lunar.Phase != "waxing" || lunar.Month != 2 || lunar.YearBE != 2566 {
		t.Errorf("Expected Waxing 10, Month 2, BE 2566, but got %+v", lunar)
	}

	// Test a known Wan Pra: 6 March 2023 (Makha Bucha - Full Moon Month 3)
	makhaBucha := time.Date(2023, 3, 6, 0, 0, 0, 0, time.UTC)
	if !IsWanPra(makhaBucha) {
		t.Errorf("Expected 2023-03-06 to be Wan Pra, but it was not")
	}
}

func TestAnalyzeName(t *testing.T) {
	// 'ก' = 1
	if AnalyzeName("ก") != 1 {
		t.Errorf("Expected 1 for 'ก', got %d", AnalyzeName("ก"))
	}
}
