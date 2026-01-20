package models

// NumberMeaning corresponds to the 'numbers' table in the database
type NumberMeaning struct {
	PairNumberID int    `json:"pairnumberid"`
	PairNumber   string `json:"pairnumber"`
	PairType     string `json:"pairtype"`
	PairPoint    int    `json:"pairpoint"`
	MiracleDesc  string `json:"miracledesc"`
	MiracleDetail string `json:"miracledetail"`
	VipDetail    string `json:"vip_detail"`
}
