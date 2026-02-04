package utils

import (
	"time"
)

type AgeResult struct {
	Year   int
	Month  int
	Day    int
}

func CalculateAge(birthTimeUnix int64, targetTimeUnix int64) AgeResult {
	birth := time.Unix(birthTimeUnix, 0).In(time.UTC)
	target := time.Unix(targetTimeUnix, 0).In(time.UTC)

	if target.Before(birth) {
		return AgeResult{}
	}

	years := target.Year() - birth.Year()
	months := int(target.Month()) - int(birth.Month())
	days := target.Day() - birth.Day()

	if days < 0 {
		months--
		// Get last day of previous month
		lastDayPrevMonth := target.AddDate(0, 0, -target.Day()).Day()
		days += lastDayPrevMonth
	}

	if months < 0 {
		years--
		months += 12
	}

	return AgeResult{
		Year:  years,
		Month: months,
		Day:   days,
	}
}

func CalculateAgeNow(birthTimeUnix int64) AgeResult {
	return CalculateAge(birthTimeUnix, time.Now().Unix())
}
