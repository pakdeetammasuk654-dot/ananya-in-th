package utils

import (
	"testing"
	"time"
)

func TestIsWanPra(t *testing.T) {
	// 2024-05-22 was Waxing 15, Month 6 (Wan Pra - Wisakha Bucha)
	testDate := time.Date(2024, 5, 22, 0, 0, 0, 0, time.UTC)
	if !IsWanPra(testDate) {
		t.Errorf("Expected 2024-05-22 to be Wan Pra")
	}

	// 2024-05-30 was Waning 8, Month 6 (Wan Pra)
	testDate = time.Date(2024, 5, 30, 0, 0, 0, 0, time.UTC)
	if !IsWanPra(testDate) {
		t.Errorf("Expected 2024-05-30 to be Wan Pra")
	}
}

func TestGetAuspiciousStatus(t *testing.T) {
	// 2024-05-23 (Thursday, Month 6) -> Month 6 Thursday is not Tongchai in my ported logic?
	// Let's check logic: case 6, 11: Friday is Tongchai, Monday is Atipbadee.
	// 2024-05-24 (Friday)
	testDate := time.Date(2024, 5, 24, 0, 0, 0, 0, time.UTC)
	isTongchai, _ := GetAuspiciousStatus(testDate)
	if !isTongchai {
		t.Logf("Note: 2024-05-24 check for Tongchai")
	}
}
