package models

type LuckyNumberV2 struct {
	LuckyDate string `gorm:"primaryKey;column:lucky_date" json:"date"`
	Num1      string `gorm:"column:num1" json:"num1"`
	Num2      string `gorm:"column:num2" json:"num2"`
	Num3      string `gorm:"column:num3" json:"num3"`
	Num4      string `gorm:"column:num4" json:"num4"`
	Num5      string `gorm:"column:num5" json:"num5"`
	Num6      string `gorm:"column:num6" json:"num6"`
}

func (LuckyNumberV2) TableName() string {
	return "luckynumber_v2"
}
