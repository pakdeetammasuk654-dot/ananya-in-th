package models

import (
	"time"
)

type WanPra struct {
	WanPraID   uint32     `gorm:"primaryKey;column:wanpra_id;autoIncrement" json:"wanpra_id"`
	WanPraDate *time.Time `gorm:"column:wanpra_date;type:date" json:"wanpra_date"`
}

func (WanPra) TableName() string {
	return "wanpra"
}

type Topic struct {
	TopicID         uint32    `gorm:"primaryKey;column:topic_id;autoIncrement" json:"topic_id"`
	HeadText        string    `gorm:"column:head_text;size:255" json:"head_text"`
	DescText        string    `gorm:"column:desc_text;size:255" json:"desc_text"`
	TagPhone        string    `gorm:"column:tag_phone;default:false;size:30" json:"tag_phone"`
	TagTabian       string    `gorm:"column:tag_tabian;default:false;size:30" json:"tag_tabian"`
	TagHome         string    `gorm:"column:tag_home;default:false;size:30" json:"tag_home"`
	TagNameSur      string    `gorm:"column:tag_namesur;default:false;size:30" json:"tag_namesur"`
	Paragraph1      string    `gorm:"column:paragraph1;type:text" json:"paragraph1"`
	Paragraph2      string    `gorm:"column:paragraph2;type:text" json:"paragraph2"`
	Paragraph3      string    `gorm:"column:paragraph3;type:text" json:"paragraph3"`
	Photo1          string    `gorm:"column:photo1;size:30" json:"photo1"`
	Photo2          string    `gorm:"column:photo2;size:30" json:"photo2"`
	Photo3          string    `gorm:"column:photo3;size:30" json:"photo3"`
	TopicDate       time.Time `gorm:"column:topic_date;autoCreateTime" json:"topic_date"`
	TopicDateUpdate time.Time `gorm:"column:topic_date_update;autoUpdateTime" json:"topic_date_update"`
	TopicAuth       string    `gorm:"column:topic_auth;size:90" json:"topic_auth"`
	PublicStatus    string    `gorm:"column:public_status;default:false;size:13" json:"public_status"`
}

func (Topic) TableName() string {
	return "topictb"
}
