package models

type MiraDo struct {
	MiraID   uint32 `gorm:"primaryKey;column:miraid;autoIncrement" json:"mira_id"`
	Activity string `gorm:"column:activity;size:30" json:"activity"`
	DayX     string `gorm:"column:dayx;size:30" json:"day_x"`
	DayY     string `gorm:"column:dayy;size:30" json:"day_y"`
	MiraRefID uint32 `gorm:"column:mira_id" json:"mira_ref_id"`
	Desc     MiraDoDesc `gorm:"foreignKey:MiraRefID;references:MiraID" json:"desc"`
}

func (MiraDo) TableName() string {
	return "miracledo"
}

type MiraDoDesc struct {
	MiraID     uint32 `gorm:"primaryKey;column:mira_id" json:"mira_id"`
	MiraDesc   string `gorm:"column:mira_desc;type:text" json:"mira_desc"`
	MiraDetail string `gorm:"column:mira_detail;type:text" json:"mira_detail"`
}

func (MiraDoDesc) TableName() string {
	return "miracledo_desc"
}
