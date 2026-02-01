package models

type Color struct {
	ColorID    int    `gorm:"primaryKey;column:colorid;autoIncrement" json:"color_id"`
	DayEng     string `gorm:"column:day_eng" json:"day_eng"`
	ColorCode1 string `gorm:"column:color_code1" json:"color_code1"`
	ColorCode2 string `gorm:"column:color_code2" json:"color_code2"`
	ColorCode3 string `gorm:"column:color_code3" json:"color_code3"`
	ColorCode4 string `gorm:"column:color_code4" json:"color_code4"`
}

func (Color) TableName() string {
	return "colortb"
}

type BagColor struct {
	BagID     int    `gorm:"primaryKey;column:bag_id;autoIncrement" json:"bag_id"`
	MemberID  int    `gorm:"column:memberid" json:"member_id"`
	Age       string `gorm:"column:age" json:"age"`
	BagColor1 string `gorm:"column:bag_color1" json:"bag_color1"`
	BagColor2 string `gorm:"column:bag_color2" json:"bag_color2"`
	BagColor3 string `gorm:"column:bag_color3" json:"bag_color3"`
	BagColor4 string `gorm:"column:bag_color4" json:"bag_color4"`
	BagColor5 string `gorm:"column:bag_color5" json:"bag_color5"`
	BagColor6 string `gorm:"column:bag_color6" json:"bag_color6"`
	BagDesc   string `gorm:"column:bag_desc" json:"bag_desc"`
}

func (BagColor) TableName() string {
	return "bagcolortb"
}
