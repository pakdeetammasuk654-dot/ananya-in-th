package models

import (
	"time"
)

type Article struct {
	ArtID       int       `gorm:"primaryKey;column:art_id;autoIncrement" json:"art_id"`
	Slug        string    `gorm:"column:slug;unique" json:"slug"`
	Title       string    `gorm:"column:title" json:"title"`
	Excerpt     string    `gorm:"column:excerpt" json:"excerpt"`
	Category    string    `gorm:"column:category" json:"category"`
	ImageURL    string    `gorm:"column:image_url" json:"image_url"`
	PublishedAt *time.Time `gorm:"column:published_at" json:"published_at"`
	IsPublished bool      `gorm:"column:is_published" json:"is_published"`
	Content     string    `gorm:"column:content" json:"content"`
	TitleShort  string    `gorm:"column:title_short" json:"title_short"`
	PinOrder    int       `gorm:"column:pin_order" json:"pin_order"`
	CreatedAt   time.Time `gorm:"column:created_at;autoCreateTime" json:"created_at"`
	UpdatedAt   time.Time `gorm:"column:updated_at;autoUpdateTime" json:"updated_at"`
}

func (Article) TableName() string {
	return "articles"
}

type Topic struct {
	TopicID         int       `gorm:"primaryKey;column:topic_id;autoIncrement" json:"topic_id"`
	HeadText        string    `gorm:"column:head_text" json:"head_text"`
	DescText        string    `gorm:"column:desc_text" json:"desc_text"`
	TagPhone        string    `gorm:"column:tag_phone" json:"tag_phone"`
	TagTabian       string    `gorm:"column:tag_tabian" json:"tag_tabian"`
	TagHome         string    `gorm:"column:tag_home" json:"tag_home"`
	TagNamesur      string    `gorm:"column:tag_namesur" json:"tag_namesur"`
	Paragraph1      string    `gorm:"column:paragraph1" json:"paragraph1"`
	Paragraph2      string    `gorm:"column:paragraph2" json:"paragraph2"`
	Paragraph3      string    `gorm:"column:paragraph3" json:"paragraph3"`
	Photo1          string    `gorm:"column:photo1" json:"photo1"`
	Photo2          string    `gorm:"column:photo2" json:"photo2"`
	Photo3          string    `gorm:"column:photo3" json:"photo3"`
	TopicDate       time.Time `gorm:"column:topic_date" json:"topic_date"`
	TopicDateUpdate time.Time `gorm:"column:topic_date_update" json:"topic_date_update"`
	TopicAuth       string    `gorm:"column:topic_auth" json:"topic_auth"`
	PublicStatus    string    `gorm:"column:public_status" json:"public_status"`
}

func (Topic) TableName() string {
	return "topictb"
}
