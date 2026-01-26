package models

// PairMeaning represents the meaning of a number pair
type PairMeaning struct {
	Pair        string `json:"pair"`
	Meaning     string `json:"meaning"`
	Description string `json:"description"`
	Points      int    `json:"points"`
}

// PhoneAnalysis represents the complete analysis of a phone number
type PhoneAnalysis struct {
	PhoneNumber string        `json:"phone_number"`
	TotalScore  int           `json:"total_score"`
	Grade       string        `json:"grade"`
	Pairs       []PairMeaning `json:"pairs"`
}
