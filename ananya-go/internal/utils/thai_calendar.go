package utils

import (
	"time"
)

type LunarDate struct {
	Day         int
	Phase       string
	Month       int
	YearBE      int
	IsSecondMonth8 bool
}

func IsWanPra(t time.Time) bool {
	lunar := GetThaiLunarDate(t)
	day := lunar.Day

	if day == 8 || day == 15 {
		return true
	}

	if lunar.Phase == "waning" && day == 14 {
		if !IsFullMonth(lunar.Month, lunar.YearBE, lunar.IsSecondMonth8) {
			return true
		}
	}

	return false
}

func GetThaiLunarDate(t time.Time) LunarDate {
	// Epoch: 1 Jan 2023 was Waxing 10, Month 2, BE 2566
	refDate := time.Date(2023, 1, 1, 0, 0, 0, 0, time.Local)
	daysDiff := int(t.Sub(refDate).Hours() / 24)

	currDay := 10
	currPhase := "waxing"
	currMonth := 2
	currYearBE := 2566
	isSecondMonth8 := false

	if daysDiff >= 0 {
		for i := 0; i < daysDiff; i++ {
			maxDays := 15
			if currPhase == "waning" {
				if IsFullMonth(currMonth, currYearBE, isSecondMonth8) {
					maxDays = 15
				} else {
					maxDays = 14
				}
			}
			currDay++
			if currDay > maxDays {
				currDay = 1
				if currPhase == "waxing" {
					currPhase = "waning"
				} else {
					currPhase = "waxing"
					resMonth, resYearBE, resIsSecond8 := NextMonth(currMonth, currYearBE, isSecondMonth8)
					currMonth = resMonth
					currYearBE = resYearBE
					isSecondMonth8 = resIsSecond8
				}
			}
		}
	} else {
		for i := 0; i > daysDiff; i-- {
			currDay--
			if currDay < 1 {
				if currPhase == "waning" {
					currPhase = "waxing"
					currDay = 15
				} else {
					currPhase = "waning"
					resMonth, resYearBE, resIsSecond8 := PrevMonth(currMonth, currYearBE, isSecondMonth8)
					currMonth = resMonth
					currYearBE = resYearBE
					isSecondMonth8 = resIsSecond8
					if IsFullMonth(currMonth, currYearBE, isSecondMonth8) {
						currDay = 15
					} else {
						currDay = 14
					}
				}
			}
		}
	}

	return LunarDate{
		Day:         currDay,
		Phase:       currPhase,
		Month:       currMonth,
		YearBE:      currYearBE,
		IsSecondMonth8: isSecondMonth8,
	}
}

func IsFullMonth(month, yearBE int, isSecondMonth8 bool) bool {
	if month == 7 && IsAthikawan(yearBE) {
		return true
	}
	if isSecondMonth8 {
		return true
	}
	return (month % 2 == 0)
}

func NextMonth(month, yearBE int, isSecondMonth8 bool) (int, int, bool) {
	if month == 8 && IsAthikamat(yearBE) && !isSecondMonth8 {
		return 8, yearBE, true
	}
	isSecondMonth8 = false
	month++
	if month > 12 {
		month = 1
		yearBE++
	}
	return month, yearBE, isSecondMonth8
}

func PrevMonth(month, yearBE int, isSecondMonth8 bool) (int, int, bool) {
	if month == 8 && IsAthikamat(yearBE) && isSecondMonth8 {
		return 8, yearBE, false
	}
	month--
	if month < 1 {
		month = 12
		yearBE--
	}
	isSecondMonth8 = (month == 8 && IsAthikamat(yearBE))
	return month, yearBE, isSecondMonth8
}

func IsAthikamat(yearBE int) bool {
	athikamatYears := []int{2566, 2569, 2571, 2574, 2577, 2579, 2582, 2585, 2587}
	for _, y := range athikamatYears {
		if y == yearBE {
			return true
		}
	}
	return false
}

func IsAthikawan(yearBE int) bool {
	athikawanYears := []int{2567, 2570, 2575}
	for _, y := range athikawanYears {
		if y == yearBE {
			return true
		}
	}
	return false
}

type AuspiciousStatus struct {
	IsTongchai   bool
	IsAtipbadee bool
}

func GetAuspiciousStatus(t time.Time) AuspiciousStatus {
	dayOfWeek := int(t.Weekday()) // 0 (Sun) - 6 (Sat)
	lunar := GetThaiLunarDate(t)
	month := lunar.Month

	status := AuspiciousStatus{
		IsTongchai:   false,
		IsAtipbadee: false,
	}

	switch month {
	case 5, 10:
		if dayOfWeek == 4 {
			status.IsTongchai = true
		}
		if dayOfWeek == 6 {
			status.IsAtipbadee = true
		}
	case 6, 11:
		if dayOfWeek == 5 {
			status.IsTongchai = true
		}
		if dayOfWeek == 1 {
			status.IsAtipbadee = true
		}
	case 7, 12:
		if dayOfWeek == 1 {
			status.IsTongchai = true
		}
		if dayOfWeek == 0 {
			status.IsAtipbadee = true
		}
	case 8, 1:
		if dayOfWeek == 3 {
			status.IsTongchai = true
		}
		if dayOfWeek == 4 {
			status.IsAtipbadee = true
		}
	case 9, 2:
		if dayOfWeek == 6 {
			status.IsTongchai = true
		}
		if dayOfWeek == 3 {
			status.IsAtipbadee = true
		}
	case 3, 4:
		if dayOfWeek == 2 {
			status.IsTongchai = true
			status.IsAtipbadee = true
		}
	}

	return status
}
