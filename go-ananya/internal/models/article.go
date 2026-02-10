package models

import (
	"time"
)

type Article struct {
	ArtID       int        `gorm:"primaryKey;column:art_id" json:"newsid"`
	Slug        string     `gorm:"column:slug;unique" json:"slug"`
	Title       string     `gorm:"column:title" json:"news_headline"`
	Excerpt     string     `gorm:"column:excerpt" json:"news_desc"`
	Category    string     `gorm:"column:category" json:"category"`
	ImageURL    string     `gorm:"column:image_url" json:"news_pic_header"`
	PublishedAt *time.Time `gorm:"column:published_at" json:"published_at"`
	IsPublished bool       `gorm:"column:is_published" json:"is_published"`
	Content     string     `gorm:"column:content" json:"news_detail"`
	TitleShort  string     `gorm:"column:title_short" json:"news_title_short"`
	PinOrder    int        `gorm:"column:pin_order" json:"pin_order"`
	CreatedAt   time.Time  `gorm:"column:created_at;autoCreateTime" json:"created_at"`
	UpdatedAt   time.Time  `gorm:"column:updated_at;autoUpdateTime" json:"updated_at"`
}

func (Article) TableName() string {
	return "articles"
}
