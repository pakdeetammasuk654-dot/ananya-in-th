package services

import (
	"go-numerology-api/internal/models"
	"strconv"
	"strings"
)

// AnalysisResult holds the result of a phone number analysis
type AnalysisResult struct {
	PhoneNumber string               `json:"phone_number"`
	TotalScore  int                  `json:"total_score"`
	Pairs       []PairAnalysis       `json:"pairs"`
	Summary     string               `json:"summary"`
}

// PairAnalysis holds the analysis for a single pair of numbers
type PairAnalysis struct {
	Pair    string `json:"pair"`
	Meaning string `json:"meaning"`
	Score   int    `json:"score"`
}

// NumerologyService provides phone number analysis functionality
type NumerologyService struct {
	NumberMeanings map[string]models.NumberMeaning
}

// NewNumerologyService creates a new NumerologyService
func NewNumerologyService(meanings map[string]models.NumberMeaning) *NumerologyService {
	return &NumerologyService{NumberMeanings: meanings}
}

// AnalyzePhoneNumber performs a numerological analysis of a given phone number
func (s *NumerologyService) AnalyzePhoneNumber(phone string) *AnalysisResult {
	// Basic cleanup of the phone number
	phone = strings.TrimSpace(phone)

	// In a real app, you might want more sophisticated validation
	if len(phone) < 2 {
		return nil // Not enough digits to form a pair
	}

	var pairs []PairAnalysis
	var totalScore int

	// Assuming 2-digit pairs for this logic
	for i := 0; i < len(phone)-1; i++ {
		pairStr := phone[i : i+2]
		meaning, found := s.NumberMeanings[pairStr]

		pairAnalysis := PairAnalysis{
			Pair:    pairStr,
			Meaning: "No specific meaning found.",
			Score:   0,
		}

		if found {
			pairAnalysis.Meaning = meaning.MiracleDesc
			pairAnalysis.Score = meaning.PairPoint
			totalScore += meaning.PairPoint
		}
		pairs = append(pairs, pairAnalysis)
	}

	// Calculate the sum of all digits in the phone number for an overall summary
	sumOfDigits := 0
	for _, char := range phone {
		digit, err := strconv.Atoi(string(char))
		if err == nil {
			sumOfDigits += digit
		}
	}

	summaryMeaning, found := s.NumberMeanings[strconv.Itoa(sumOfDigits)]
	summary := "Overall, this number is neutral."
	if found {
		summary = summaryMeaning.MiracleDesc
	}


	return &AnalysisResult{
		PhoneNumber: phone,
		TotalScore:  totalScore,
		Pairs:       pairs,
		Summary:     summary,
	}
}
