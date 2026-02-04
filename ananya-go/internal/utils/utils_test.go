package utils

import (
	"testing"
	"time"
)

func TestIsWanPra(t *testing.T) {
	// 2023-01-06 should be Wan Pra (Waxing 15)
	if !IsWanPra("2023-01-06") {
		t.Errorf("Expected 2023-01-06 to be Wan Pra")
	}
}

func TestCalculateAge(t *testing.T) {
	birth, _ := time.Parse("2006-01-02", "1982-07-04")
	now, _ := time.Parse("2006-01-02", "2024-07-04")

	age := CalculateAge(birth.Unix(), now.Unix())
	if age.Year != 42 {
		t.Errorf("Expected age 42, got %d", age.Year)
	}
}
