package utils

import (
	"time"
)

type LunarDate struct {
	Day        int
	Phase      string
	Month      int
	YearBE     int
	IsSecond8  bool
}

func IsWanPra(t time.Time) bool {
	lunar := GetThaiLunarDate(t)
	day := lunar.Day

	if day == 8 || day == 15 {
		return true
	}

	if lunar.Phase == "waning" && day == 14 {
		if !isFullMonth(lunar.Month, lunar.YearBE, lunar.IsSecond8) {
			return true
		}
	}
	return false
}

func GetThaiLunarDate(t time.Time) LunarDate {
	refDate := time.Date(2023, 1, 1, 0, 0, 0, 0, time.UTC)
	daysDiff := int(t.Sub(refDate).Hours() / 24)

	currDay := 10
	currPhase := "waxing"
	currMonth := 2
	currYearBE := 2566
	isSecond8 := false

	if daysDiff >= 0 {
		for i := 0; i < daysDiff; i++ {
			maxDays := 15
			if currPhase == "waning" {
				if isFullMonth(currMonth, currYearBE, isSecond8) {
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
					currMonth, currYearBE, isSecond8 = nextMonth(currMonth, currYearBE, isSecond8)
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
					currMonth, currYearBE, isSecond8 = prevMonth(currMonth, currYearBE, isSecond8)
					if isFullMonth(currMonth, currYearBE, isSecond8) {
						currDay = 15
					} else {
						currDay = 14
					}
				}
			}
		}
	}

	return LunarDate{
		Day:       currDay,
		Phase:     currPhase,
		Month:     currMonth,
		YearBE:    currYearBE,
		IsSecond8: isSecond8,
	}
}

func isFullMonth(month, yearBE int, isSecond8 bool) bool {
	if month == 7 && IsAthikawan(yearBE) {
		return true
	}
	if isSecond8 {
		return true
	}
	return month%2 == 0
}

func nextMonth(month, yearBE int, isSecond8 bool) (int, int, bool) {
	if month == 8 && IsAthikamat(yearBE) && !isSecond8 {
		return 8, yearBE, true
	}
	isSecond8 = false
	month++
	if month > 12 {
		month = 1
		yearBE++
	}
	return month, yearBE, isSecond8
}

func prevMonth(month, yearBE int, isSecond8 bool) (int, int, bool) {
	if month == 8 && IsAthikamat(yearBE) && isSecond8 {
		return 8, yearBE, false
	}
	month--
	if month < 1 {
		month = 12
		yearBE--
	}
	isSecond8 = (month == 8 && IsAthikamat(yearBE))
	return month, yearBE, isSecond8
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

func GetAuspiciousStatus(t time.Time) (bool, bool) {
	dayOfWeek := int(t.Weekday())
	lunar := GetThaiLunarDate(t)
	month := lunar.Month

	isTongchai := false
	isAtipbadee := false

	switch month {
	case 5, 10:
		if dayOfWeek == 4 { isTongchai = true }
		if dayOfWeek == 6 { isAtipbadee = true }
	case 6, 11:
		if dayOfWeek == 5 { isTongchai = true }
		if dayOfWeek == 1 { isAtipbadee = true }
	case 7, 12:
		if dayOfWeek == 1 { isTongchai = true }
		if dayOfWeek == 0 { isAtipbadee = true }
	case 8, 1:
		if dayOfWeek == 3 { isTongchai = true }
		if dayOfWeek == 4 { isAtipbadee = true }
	case 9, 2:
		if dayOfWeek == 6 { isTongchai = true }
		if dayOfWeek == 3 { isAtipbadee = true }
	case 3, 4:
		if dayOfWeek == 2 {
			isTongchai = true
			isAtipbadee = true
		}
	}
	return isTongchai, isAtipbadee
}
