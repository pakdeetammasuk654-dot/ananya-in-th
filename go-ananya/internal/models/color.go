package models

type Color struct {
	ColorID    int    `gorm:"primaryKey;column:colorid" json:"colorid"`
	DayEng     string `gorm:"column:day_eng" json:"day_eng"`
	ColorCode1 string `gorm:"column:color_code1" json:"color_code1"`
	ColorCode2 string `gorm:"column:color_code2" json:"color_code2"`
	ColorCode3 string `gorm:"column:color_code3" json:"color_code3"`
	ColorCode4 string `gorm:"column:color_code4" json:"color_code4"`
}

func (Color) TableName() string {
	return "colortb"
}
