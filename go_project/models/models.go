package models

// Number represents the data structure for a number from the database.
type Number struct {
	PairNumberID   int    `json:"pair_number_id"`
	PairNumber     string `json:"pair_number"`
	PairType       string `json:"pair_type"`
	PairPoint      int    `json:"pair_point"`
	MiracleDesc    string `json:"miracle_desc"`
	MiracleDetail  string `json:"miracle_detail"`
}

// AnalysisResult represents the final JSON structure of the phone number analysis.
type AnalysisResult struct {
	ScoreTotal          map[string]int      `json:"scoreTotal"`
	PercentPosition     map[string]int      `json:"percentPosition"`
	SummaryMiracleScore []map[string]interface{} `json:"summaryMiracleScore"`
	ScoreLastPairA      map[string]int      `json:"scoreLastPairA"`
	ScoreContinueA      map[string]int      `json:"scoreContinueA"`
	ScoreContinueB      map[string]int      `json:"scoreContinueB"`
	PairsA              []PairDetail        `json:"pairsA"`
	PairsB              []PairDetail        `json:"pairsB"`
	PairSum             PairDetail          `json:"pairSum"`
	PairMiracle         []MiracleDetail     `json:"pairMiracle"`
}

// PairDetail holds information about a number pair and its percentile.
type PairDetail struct {
	PairNumber string `json:"pairNumber"`
	PairType   string `json:"pairType"`
	PairPoint  int    `json:"pairPoint"`
	Percentile int    `json:"percentile"`
}

// MiracleDetail holds detailed information about a miracle number.
type MiracleDetail struct {
	PairNumber    string `json:"pairNumber"`
	PairType      string `json:"pairType"`
	PairPoint     int    `json:"pairPoint"`
	Percentile    int    `json:"percentile"`
	MiracleDesc   string `json:"miracleDesc"`
	MiracleDetail string `json:"miracleDetail"`
}
