package models

type MiracleDo struct {
	MiraID   int    `gorm:"primaryKey;column:miraid;autoIncrement" json:"mira_id"`
	Activity string `gorm:"column:activity" json:"activity"`
	DayX     string `gorm:"column:dayx" json:"day_x"`
	DayY     string `gorm:"column:dayy" json:"day_y"`
	Action   int    `gorm:"column:action;default:1" json:"action"`
	MiraRef  string `gorm:"column:mira_id" json:"mira_ref"`
}

func (MiracleDo) TableName() string {
	return "miracledo"
}

type MiracleDoDesc struct {
	MiraID   int    `gorm:"primaryKey;column:mira_id;autoIncrement" json:"mira_id"`
	Activity string `gorm:"column:activity" json:"activity"`
	MiraDay  string `gorm:"column:mira_day" json:"mira_day"`
	MiraDesc string `gorm:"column:mira_desc" json:"mira_desc"`
}

func (MiracleDoDesc) TableName() string {
	return "miracledo_desc"
}
