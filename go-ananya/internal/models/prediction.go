package models

type MiraDo struct {
	MiraID   int    `gorm:"primaryKey;column:miraid"`
	Activity string `gorm:"column:activity"`
	DayX     string `gorm:"column:dayx"`
	DayY     string `gorm:"column:dayy"`
	MiraDesc string `gorm:"column:miradesc"`
}

func (MiraDo) TableName() string {
	return "miracledo"
}
