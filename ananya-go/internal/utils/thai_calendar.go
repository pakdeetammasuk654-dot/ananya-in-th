package utils

import (
	"math"
	"time"
)

type ThaiLunarDate struct {
	Day        int    `json:"day"`
	Phase      string `json:"phase"`
	Month      int    `json:"month"`
	YearBE     int    `json:"year_be"`
	IsSecond8  bool   `json:"is_second_8"`
}

func GetThaiLunarDate(t time.Time) ThaiLunarDate {
	refDate := time.Date(2023, 1, 1, 0, 0, 0, 0, time.UTC)
	daysDiff := int(math.Round(t.Sub(refDate).Hours() / 24))

	currDay := 10
	currPhase := "waxing"
	currMonth := 2
	currYearBE := 2566
	isSecondMonth8 := false

	if daysDiff >= 0 {
		for i := 0; i < daysDiff; i++ {
			maxDays := 15
			if currPhase == "waning" {
				if isFullMonth(currMonth, currYearBE, isSecondMonth8) {
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
					currMonth, currYearBE, isSecondMonth8 = nextMonth(currMonth, currYearBE, isSecondMonth8)
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
					currMonth, currYearBE, isSecondMonth8 = prevMonth(currMonth, currYearBE, isSecondMonth8)
					if isFullMonth(currMonth, currYearBE, isSecondMonth8) {
						currDay = 15
					} else {
						currDay = 14
					}
				}
			}
		}
	}

	return ThaiLunarDate{
		Day:       currDay,
		Phase:     currPhase,
		Month:     currMonth,
		YearBE:    currYearBE,
		IsSecond8: isSecondMonth8,
	}
}

func isFullMonth(month int, yearBE int, isSecondMonth8 bool) bool {
	if month == 7 && isAthikawan(yearBE) {
		return true
	}
	if isSecondMonth8 {
		return true
	}
	return month%2 == 0
}

func nextMonth(month int, yearBE int, isSecond8 bool) (int, int, bool) {
	if month == 8 && isAthikamat(yearBE) && !isSecond8 {
		return 8, yearBE, true
	}
	month++
	if month > 12 {
		month = 1
		yearBE++
	}
	return month, yearBE, false
}

func prevMonth(month int, yearBE int, isSecond8 bool) (int, int, bool) {
	if month == 8 && isAthikamat(yearBE) && isSecond8 {
		return 8, yearBE, false
	}
	month--
	if month < 1 {
		month = 12
		yearBE--
	}
	isSecond8 = (month == 8 && isAthikamat(yearBE))
	return month, yearBE, isSecond8
}

func isAthikamat(yearBE int) bool {
	athikamatYears := []int{2566, 2569, 2571, 2574, 2577, 2579, 2582, 2585, 2587}
	for _, y := range athikamatYears {
		if y == yearBE {
			return true
		}
	}
	return false
}

func isAthikawan(yearBE int) bool {
	athikawanYears := []int{2567, 2570, 2575}
	for _, y := range athikawanYears {
		if y == yearBE {
			return true
		}
	}
	return false
}

func IsWanPra(t time.Time) bool {
	lunar := GetThaiLunarDate(t)
	if lunar.Day == 8 || lunar.Day == 15 {
		return true
	}
	if lunar.Phase == "waning" && lunar.Day == 14 {
		if !isFullMonth(lunar.Month, lunar.YearBE, lunar.IsSecond8) {
			return true
		}
	}
	return false
}
