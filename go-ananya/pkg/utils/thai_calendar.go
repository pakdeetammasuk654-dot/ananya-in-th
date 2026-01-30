package utils

import (
	"time"
)

type ThaiLunarDate struct {
	Day        int    `json:"day"`
	Phase      string `json:"phase"`
	Month      int    `json:"month"`
	YearBE     int    `json:"year_be"`
	IsSecond8  bool   `json:"is_second_8"`
}

type AuspiciousStatus struct {
	IsTongchai   bool `json:"is_tongchai"`
	IsAtipbadee bool `json:"is_atipbadee"`
}

func GetThaiLunarDate(dateStr string) ThaiLunarDate {
	layout := "2006-01-02"
	date, _ := time.Parse(layout, dateStr)
	refDate, _ := time.Parse(layout, "2023-01-01")

	// Calculate days difference safely
	diff := date.Unix() - refDate.Unix()
	daysDiff := int(diff / 86400)

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
					// fmt.Printf("Month end: %d/%d, full: %v\n", currMonth, currYearBE, isFullMonth(currMonth, currYearBE, isSecondMonth8))
					currPhase = "waxing"
					resMonth, resYear, resIs8_2 := nextMonth(currMonth, currYearBE, isSecondMonth8)
					currMonth = resMonth
					currYearBE = resYear
					isSecondMonth8 = resIs8_2
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
					resMonth, resYear, resIs8_2 := prevMonth(currMonth, currYearBE, isSecondMonth8)
					currMonth = resMonth
					currYearBE = resYear
					isSecondMonth8 = resIs8_2
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

func nextMonth(month int, yearBE int, isSecondMonth8 bool) (int, int, bool) {
	if month == 8 && isAthikamat(yearBE) && !isSecondMonth8 {
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

func prevMonth(month int, yearBE int, isSecondMonth8 bool) (int, int, bool) {
	if month == 8 && isAthikamat(yearBE) && isSecondMonth8 {
		return 8, yearBE, false
	}
	month--
	if month < 1 {
		month = 12
		yearBE--
	}
	isSecondMonth8 = (month == 8 && isAthikamat(yearBE))
	return month, yearBE, isSecondMonth8
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
	athikawanYears := []int{2570, 2575} // Removed 2567 as it's not Athikawan in standard calendar
	for _, y := range athikawanYears {
		if y == yearBE {
			return true
		}
	}
	return false
}

func IsWanPra(dateStr string) bool {
	lunar := GetThaiLunarDate(dateStr)
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

func GetAuspiciousStatus(dateStr string) AuspiciousStatus {
	layout := "2006-01-02"
	date, _ := time.Parse(layout, dateStr)
	dayOfWeek := int(date.Weekday()) // 0 (Sun) - 6 (Sat)
	lunar := GetThaiLunarDate(dateStr)
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
