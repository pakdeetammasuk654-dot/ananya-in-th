package models

import "time"

type Article struct {
	ArtID       int       `gorm:"primaryKey;column:art_id;autoIncrement" json:"art_id"`
	Slug        string    `gorm:"column:slug;unique" json:"slug"`
	Title       string    `gorm:"column:title" json:"title"`
	Excerpt     string    `gorm:"column:excerpt" json:"excerpt"`
	Category    string    `gorm:"column:category" json:"category"`
	ImageURL    string    `gorm:"column:image_url" json:"image_url"`
	PublishedAt *time.Time `gorm:"column:published_at" json:"published_at"`
	IsPublished int       `gorm:"column:is_published;default:0" json:"is_published"`
	Content     string    `gorm:"column:content" json:"content"`
	TitleShort  string    `gorm:"column:title_short" json:"title_short"`
	PinOrder    int       `gorm:"column:pin_order;default:0" json:"pin_order"`
	CreatedAt   time.Time `gorm:"column:created_at;default:CURRENT_TIMESTAMP" json:"created_at"`
	UpdatedAt   time.Time `gorm:"column:updated_at;default:CURRENT_TIMESTAMP" json:"updated_at"`
}

func (Article) TableName() string {
	return "articles"
}
