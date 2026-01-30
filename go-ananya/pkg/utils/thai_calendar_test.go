package utils

import (
	"testing"
)

func TestIsWanPra(t *testing.T) {
	// 2024-07-20 was Waxing 15, Month 8
	if !IsWanPra("2024-07-20") {
		t.Errorf("Expected 2024-07-20 to be Wan Pra")
	}

	// 2024-07-21 was Waning 1 (not a standard Wan Pra)
	// But it was Khao Phansa. Standard logic says false.
	if IsWanPra("2024-07-21") {
		t.Errorf("Expected 2024-07-21 not to be Wan Pra")
	}
}

func TestGetAuspiciousStatus(t *testing.T) {
	// 2023-08-01 was Full Moon (Waxing 15, Month 8-2).
	// Month 8, Day of week Tuesday (2).
	// According to rules: Case 9, 2 (Month 9 or 2) ...
	// Case 8, 1 (Month 8 or 1) -> Wednesday (3) Tongchai, Thursday (4) Atipbadee.

	// Let's test a date where it should be true.
	// For Month 8, Wednesday is Tongchai.
	// July 12 2023 was Wednesday, Month 8-1.
	status := GetAuspiciousStatus("2023-07-12")
	if !status.IsTongchai {
		t.Errorf("Expected 2023-07-12 to be Tongchai")
	}
}
