package models

import (
	"time"
)

type Article struct {
	ArtID       uint32    `gorm:"primaryKey;column:art_id;autoIncrement" json:"art_id"`
	Slug        string    `gorm:"column:slug;unique;size:255" json:"slug"`
	Title       string    `gorm:"column:title;not null;size:255" json:"title"`
	Excerpt     string    `gorm:"column:excerpt;type:text" json:"excerpt"`
	Category    string    `gorm:"column:category;size:255" json:"category"`
	ImageURL    string    `gorm:"column:image_url;size:255" json:"image_url"`
	PublishedAt *time.Time `gorm:"column:published_at" json:"published_at"`
	IsPublished bool      `gorm:"column:is_published;default:0" json:"is_published"`
	Content     string    `gorm:"column:content;type:text" json:"content"`
	TitleShort  string    `gorm:"column:title_short;size:255" json:"title_short"`
	PinOrder    int32     `gorm:"column:pin_order;default:0" json:"pin_order"`
	CreatedAt   time.Time `gorm:"column:created_at;autoCreateTime" json:"created_at"`
	UpdatedAt   time.Time `gorm:"column:updated_at;autoUpdateTime" json:"updated_at"`
}

func (Article) TableName() string {
	return "articles"
}
