package models

type BagColor struct {
	BagID     uint32 `gorm:"primaryKey;column:bag_id;autoIncrement" json:"bag_id"`
	MemberID  uint32 `gorm:"column:memberid" json:"member_id"`
	Age       string `gorm:"column:age;size:3" json:"age"`
	BagColor1 string `gorm:"column:bag_color1;size:30" json:"bag_color1"`
	BagColor2 string `gorm:"column:bag_color2;size:30" json:"bag_color2"`
	BagColor3 string `gorm:"column:bag_color3;size:30" json:"bag_color3"`
	BagColor4 string `gorm:"column:bag_color4;size:30" json:"bag_color4"`
	BagColor5 string `gorm:"column:bag_color5;size:30" json:"bag_color5"`
	BagColor6 string `gorm:"column:bag_color6;size:30" json:"bag_color6"`
	BagDesc   string `gorm:"column:bag_desc;type:text" json:"bag_desc"`
}

func (BagColor) TableName() string {
	return "bagcolortb"
}

type Color struct {
	ColorID    uint32 `gorm:"primaryKey;column:colorid;autoIncrement" json:"color_id"`
	DayEng     string `gorm:"column:day_eng;size:30" json:"day_eng"`
	ColorCode1 string `gorm:"column:color_code1;size:30" json:"color_code1"`
	ColorCode2 string `gorm:"column:color_code2;size:30" json:"color_code2"`
	ColorCode3 string `gorm:"column:color_code3;size:15" json:"color_code3"`
	ColorCode4 string `gorm:"column:color_code4;size:30" json:"color_code4"`
}

func (Color) TableName() string {
	return "colortb"
}
